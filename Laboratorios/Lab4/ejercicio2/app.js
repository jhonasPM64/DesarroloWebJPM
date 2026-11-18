"use strict";

const contenido = document.querySelector("#contenido");
const modal = document.querySelector("#modal");
const modalTitulo = document.querySelector("#modal-titulo");
const modalCuerpo = document.querySelector("#modal-cuerpo");
const modalConfirmar = document.querySelector("#modal-confirmar");
const modalCerrar = document.querySelector("#modal-cerrar");
let debounceId = null;

function escapar(texto) {
  const div = document.createElement("div");
  div.textContent = texto == null ? "" : String(texto);
  return div.innerHTML;
}

async function cargarVista(url) {
  contenido.textContent = "";
  const cargando = document.createElement("p");
  cargando.classList.add("cargando");
  cargando.textContent = "Cargando...";
  contenido.appendChild(cargando);
  try {
    const respuesta = await fetch(url);
    if (!respuesta.ok) throw new Error(`Error HTTP ${respuesta.status}`);
    const html = await respuesta.text();
    contenido.innerHTML = html;
  } catch (error) {
    contenido.textContent = "";
    const aviso = document.createElement("p");
    aviso.classList.add("aviso", "aviso-error");
    aviso.textContent = `No se pudo cargar la vista: ${error.message}`;
    contenido.appendChild(aviso);
  }
}

document.querySelectorAll("[data-vista]").forEach((boton) => {
  boton.addEventListener("click", () => {
    document.querySelectorAll("[data-vista]").forEach((item) => item.classList.remove("activo"));
    boton.classList.add("activo");
    if (boton.dataset.vista === "inicio") cargarVista("inicio.html");
    if (boton.dataset.vista === "menu") cargarVistaMenu();
    if (boton.dataset.vista === "nuevo") cargarVista("form_producto.html").then(() => cargarCategoriasFormulario());
  });
});

async function cargarVistaMenu() {
  const plantilla = `
    <section class="panel">
      <h2>Menú <span id="contador-menu" class="contador">0</span></h2>
      <div class="barra">
        <div class="campo"><label for="buscar-menu">Buscar</label><input id="buscar-menu" type="search" placeholder="Nombre"></div>
        <div class="campo"><label for="categoria-menu">Categoría</label><select id="categoria-menu"><option value="0">Todas</option></select></div>
      </div>
      <div id="lista-productos" class="grilla"></div>
    </section>`;
  contenido.innerHTML = plantilla;
  const select = document.querySelector("#categoria-menu");
  try {
    const respuesta = await fetch("categorias.php");
    if (!respuesta.ok) throw new Error(`Error HTTP ${respuesta.status}`);
    const categorias = await respuesta.json();
    categorias.forEach((categoria) => {
      const opcion = document.createElement("option");
      opcion.value = String(categoria.id);
      opcion.textContent = categoria.nombre;
      select.appendChild(opcion);
    });
  } catch (error) {
    mostrarErrorLista(error.message);
  }
  const buscar = document.querySelector("#buscar-menu");
  buscar.addEventListener("input", () => {
    clearTimeout(debounceId);
    debounceId = setTimeout(() => consultarProductos(), 300);
  });
  select.addEventListener("change", consultarProductos);
  consultarProductos();
}

function mostrarErrorLista(mensaje) {
  const lista = document.querySelector("#lista-productos");
  if (!lista) return;
  lista.textContent = "";
  const aviso = document.createElement("p");
  aviso.classList.add("aviso", "aviso-error");
  aviso.textContent = `No se pudieron cargar los productos: ${mensaje}`;
  lista.appendChild(aviso);
}

async function consultarProductos() {
  const buscar = document.querySelector("#buscar-menu");
  const categoria = document.querySelector("#categoria-menu");
  if (!buscar || !categoria) return;
  const parametros = new URLSearchParams({ buscar: buscar.value, categoria: categoria.value });
  const lista = document.querySelector("#lista-productos");
  lista.innerHTML = `<p class="cargando">Cargando productos...</p>`;
  try {
    const respuesta = await fetch(`productos.php?${parametros}`);
    if (!respuesta.ok) throw new Error(`Error HTTP ${respuesta.status}`);
    const productos = await respuesta.json();
    document.querySelector("#contador-menu").textContent = String(productos.length);
    if (productos.length === 0) {
      lista.innerHTML = `<p class="vacio">SIN RESULTADOS</p>`;
      return;
    }
    lista.innerHTML = productos.map((producto) => {
      const claseCategoria = `et-${producto.categoria.toLowerCase()}`;
      const claseAgotado = producto.disponible ? "" : " agotado";
      const etiquetaAgotado = producto.disponible ? "" : `<span class="etiqueta et-agotado">Agotado</span>`;
      const foto = producto.imagen
        ? `<img src="../images/${escapar(producto.imagen)}" alt="${escapar(producto.nombre)}">`
        : `<div class="sin-imagen">SIN FOTO</div>`;
      return `<article class="producto${claseAgotado}" data-id="${producto.id}">
        ${foto}<div class="cuerpo">
        <span class="etiqueta ${claseCategoria}">${escapar(producto.categoria)}</span>
        ${etiquetaAgotado}<h4>${escapar(producto.nombre)}</h4>
        <span class="precio">Bs ${Number(producto.precio).toFixed(2)}</span>
        <div class="acciones">
          <button type="button" class="boton mini btn-detalle" data-id="${producto.id}">Detalle</button>
          <button type="button" class="boton mini boton-no btn-eliminar" data-id="${producto.id}">Eliminar</button>
        </div></div></article>`;
    }).join("");
  } catch (error) {
    mostrarErrorLista(error.message);
  }
}

async function cargarCategoriasFormulario() {
  const select = document.querySelector("#categoria_id");
  if (!select) return;
  try {
    const respuesta = await fetch("categorias.php");
    if (!respuesta.ok) throw new Error(`Error HTTP ${respuesta.status}`);
    const categorias = await respuesta.json();
    categorias.forEach((categoria) => {
      const opcion = document.createElement("option");
      opcion.value = String(categoria.id);
      opcion.textContent = categoria.nombre;
      select.appendChild(opcion);
    });
  } catch (error) {
    const contenedor = document.querySelector("#errores-alta");
    contenedor.textContent = "";
    contenedor.classList.add("aviso", "aviso-error");
    contenedor.textContent = `No se pudieron cargar las categorías: ${error.message}`;
  }
}

function abrirModal(titulo) {
  modalTitulo.textContent = titulo;
  modal.classList.add("abierto");
  modalConfirmar.classList.add("oculto");
}
function cerrarModal() {
  modal.classList.remove("abierto");
  modalConfirmar.classList.add("oculto");
  modalCuerpo.textContent = "";
  modalTitulo.textContent = "";
}

contenido.addEventListener("click", async (evento) => {
  const detalle = evento.target.closest(".btn-detalle");
  if (detalle) {
    try {
      const respuesta = await fetch(`producto.php?id=${detalle.dataset.id}`);
      if (!respuesta.ok) throw new Error(`Error HTTP ${respuesta.status}`);
      const producto = await respuesta.json();
      abrirModal("Detalle del producto");
      modalCuerpo.textContent = "";
      if (producto.imagen) {
        const imagen = document.createElement("img");
        imagen.src = `../images/${producto.imagen}`;
        imagen.alt = producto.nombre;
        modalCuerpo.appendChild(imagen);
      } else {
        const sinFoto = document.createElement("div");
        sinFoto.classList.add("sin-imagen");
        sinFoto.textContent = "SIN FOTO";
        modalCuerpo.appendChild(sinFoto);
      }
      const categoria = document.createElement("p");
      categoria.textContent = `Categoría: ${producto.categoria}`;
      const nombre = document.createElement("h3");
      nombre.textContent = producto.nombre;
      const descripcion = document.createElement("p");
      descripcion.textContent = producto.descripcion;
      const precio = document.createElement("p");
      precio.classList.add("precio");
      precio.textContent = `Bs ${Number(producto.precio).toFixed(2)}`;
      modalCuerpo.append(nombre, categoria, descripcion, precio);
    } catch (error) {
      abrirModal("Error");
      const aviso = document.createElement("p");
      aviso.classList.add("aviso", "aviso-error");
      aviso.textContent = `No se pudo cargar el detalle: ${error.message}`;
      modalCuerpo.appendChild(aviso);
    }
  }

  const eliminar = evento.target.closest(".btn-eliminar");
  if (eliminar) {
    abrirModal("Confirmar eliminación");
    modalCuerpo.textContent = "";
    const texto = document.createElement("p");
    texto.textContent = "¿Está seguro de que desea eliminar este producto?";
    modalCuerpo.appendChild(texto);
    modalConfirmar.classList.remove("oculto");
    modalConfirmar.dataset.id = eliminar.dataset.id;
  }
});

modalConfirmar.addEventListener("click", async () => {
  const id = modalConfirmar.dataset.id;
  const datos = new FormData();
  datos.append("id", id);
  try {
    const respuesta = await fetch("eliminar.php", { method: "POST", body: datos });
    const resultado = await respuesta.json();
    if (!respuesta.ok) throw new Error(resultado.mensaje || `Error HTTP ${respuesta.status}`);
    const tarjeta = contenido.querySelector(`.producto[data-id="${id}"]`);
    if (tarjeta) tarjeta.remove();
    const contador = document.querySelector("#contador-menu");
    if (contador) contador.textContent = String(Math.max(0, Number(contador.textContent) - 1));
    cerrarModal();
    const aviso = document.createElement("p");
    aviso.classList.add("aviso");
    aviso.textContent = resultado.mensaje;
    contenido.prepend(aviso);
  } catch (error) {
    const aviso = document.createElement("p");
    aviso.classList.add("aviso", "aviso-error");
    aviso.textContent = `No se pudo eliminar: ${error.message}`;
    modalCuerpo.textContent = "";
    modalCuerpo.appendChild(aviso);
  }
});

modalCerrar.addEventListener("click", cerrarModal);
modal.addEventListener("click", (evento) => {
  if (evento.target === modal) cerrarModal();
});
document.addEventListener("keydown", (evento) => {
  if (evento.key === "Escape") cerrarModal();
});

contenido.addEventListener("submit", async (evento) => {
  if (!evento.target.matches("#form-producto")) return;
  evento.preventDefault();
  const form = evento.target;
  const nombre = form.querySelector("#nombre");
  const categoria = form.querySelector("#categoria_id");
  const precio = form.querySelector("#precio");
  const nombreValido = nombre.value.trim().length >= 3;
  const categoriaValida = categoria.value !== "";
  const precioValido = Number(precio.value) > 0;
  nombre.classList.toggle("valido", nombreValido);
  nombre.classList.toggle("invalido", !nombreValido);
  categoria.classList.toggle("valido", categoriaValida);
  categoria.classList.toggle("invalido", !categoriaValida);
  precio.classList.toggle("valido", precioValido);
  precio.classList.toggle("invalido", !precioValido);
  form.querySelector("#error-nombre").textContent = nombreValido ? "" : "Mínimo 3 caracteres.";
  form.querySelector("#error-categoria").textContent = categoriaValida ? "" : "Seleccione una categoría.";
  form.querySelector("#error-precio").textContent = precioValido ? "" : "Ingrese un precio mayor que cero.";
  if (!nombreValido || !categoriaValida || !precioValido) return;

  const boton = form.querySelector("#btn-guardar");
  const contenedor = form.querySelector("#errores-alta");
  contenedor.textContent = "";
  contenedor.classList.remove("aviso", "aviso-error");
  boton.disabled = true;
  boton.textContent = "Guardando...";
  try {
    const respuesta = await fetch("insertar.php", { method: "POST", body: new FormData(form) });
    const resultado = await respuesta.json();
    if (respuesta.status === 201) {
      contenedor.classList.add("aviso");
      contenedor.textContent = resultado.mensaje;
      form.reset();
      [nombre, categoria, precio].forEach((input) => input.classList.remove("valido", "invalido"));
    } else if (respuesta.status === 422) {
      contenedor.classList.add("aviso", "aviso-error");
      const lista = document.createElement("ul");
      lista.classList.add("lista-errores");
      (resultado.errores || []).forEach((texto) => {
        const item = document.createElement("li");
        item.textContent = texto;
        lista.appendChild(item);
      });
      contenedor.appendChild(lista);
    } else {
      throw new Error(resultado.mensaje || `Error HTTP ${respuesta.status}`);
    }
  } catch (error) {
    contenedor.classList.add("aviso", "aviso-error");
    contenedor.textContent = `No se pudo guardar el producto: ${error.message}`;
  } finally {
    boton.disabled = false;
    boton.textContent = "Guardar producto";
  }
});

cargarVista("inicio.html");
