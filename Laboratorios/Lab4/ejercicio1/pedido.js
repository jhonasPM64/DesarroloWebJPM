"use strict";

const productos = [
  { id: 1, nombre: "Empanadas de queso", categoria: "Salados", precio: 6, imagen: "../images/empanadas.jpg" },
  { id: 2, nombre: "Papas fritas", categoria: "Salados", precio: 8, imagen: "../images/papas.jpg" },
  { id: 3, nombre: "Croquetas de pollo", categoria: "Salados", precio: 10, imagen: "../images/croquetas.jpg" },
  { id: 4, nombre: "Sopa de pollo", categoria: "Platos", precio: 12, imagen: "../images/sopa.jpg" },
  { id: 5, nombre: "Pollo al horno", categoria: "Platos", precio: 22, imagen: "../images/pollo.jpg" },
  { id: 6, nombre: "Spaghetti carbonara", categoria: "Platos", precio: 20, imagen: "../images/espagueti.jpg" },
  { id: 7, nombre: "Flan casero", categoria: "Postres", precio: 7, imagen: "../images/flan.jpg" },
  { id: 8, nombre: "Tiramisú", categoria: "Postres", precio: 12, imagen: "../images/tiramisu.jpg" },
  { id: 9, nombre: "Brownie", categoria: "Postres", precio: 8, imagen: "../images/brownis.jpg" }
];

let pedido = [];
const catalogo = document.querySelector("#catalogo");
const detalle = document.querySelector("#detalle");
const cuerpoDetalle = detalle.querySelector("tbody");
const resumen = document.querySelector("#resumen-resultados");
const buscar = document.querySelector("#buscar");
const categoria = document.querySelector("#categoria");
const entrega = document.querySelector("#entrega");
const campoAula = document.querySelector("#campo-aula");
const form = document.querySelector("#form-entrega");

function mostrarProductos(lista) {
  catalogo.textContent = "";
  lista.forEach((producto) => {
    const tarjeta = document.createElement("article");
    tarjeta.classList.add("producto");

    const imagen = document.createElement("img");
    imagen.src = producto.imagen;
    imagen.alt = producto.nombre;

    const cuerpo = document.createElement("div");
    cuerpo.classList.add("cuerpo");
    const etiqueta = document.createElement("span");
    etiqueta.classList.add("etiqueta");
    if (producto.categoria === "Salados") etiqueta.classList.add("et-salados");
    if (producto.categoria === "Platos") etiqueta.classList.add("et-platos");
    if (producto.categoria === "Postres") etiqueta.classList.add("et-postres");
    etiqueta.textContent = producto.categoria;
    const titulo = document.createElement("h4");
    titulo.textContent = producto.nombre;
    const precio = document.createElement("span");
    precio.classList.add("precio");
    precio.textContent = `Bs ${producto.precio.toFixed(2)}`;
    const acciones = document.createElement("div");
    acciones.classList.add("acciones");
    const boton = document.createElement("button");
    boton.type = "button";
    boton.classList.add("boton", "mini", "btn-agregar");
    boton.dataset.id = String(producto.id);
    boton.textContent = "Agregar";
    acciones.appendChild(boton);
    cuerpo.append(etiqueta, titulo, precio, acciones);
    tarjeta.append(imagen, cuerpo);
    catalogo.appendChild(tarjeta);
  });

  if (lista.length === 0) {
    const aviso = document.createElement("p");
    aviso.classList.add("aviso", "aviso-error");
    aviso.textContent = "No hay productos que coincidan con la búsqueda.";
    catalogo.appendChild(aviso);
    resumen.textContent = "Mostrando 0 de 9 productos";
  } else {
    resumen.textContent = `Mostrando ${lista.length} de 9 productos`;
  }
}

function filtrar() {
  const texto = buscar.value.trim().toLowerCase();
  const seleccion = categoria.value;
  const lista = productos.filter((producto) =>
    producto.nombre.toLowerCase().includes(texto) &&
    (seleccion === "Todas" || producto.categoria === seleccion)
  );
  mostrarProductos(lista);
}

function guardarPedido() {
  localStorage.setItem("pedidoCafeteria", JSON.stringify(pedido));
}

function renderizarPedido() {
  const filas = pedido.map((item) => {
    const subtotal = item.precio * item.cantidad;
    return `<tr><td>${item.nombre}</td><td><button type="button" class="boton mini boton-sec btn-quitar" data-id="${item.id}">-</button> ${item.cantidad}</td><td class="num">Bs ${subtotal.toFixed(2)}</td></tr>`;
  });
  cuerpoDetalle.innerHTML = filas.join("");
  const cantidad = pedido.reduce((total, item) => total + item.cantidad, 0);
  const total = pedido.reduce((sum, item) => sum + item.precio * item.cantidad, 0);
  document.querySelector("#contador-pedido").textContent = String(cantidad);
  document.querySelector("#total-pedido").textContent = `Bs ${total.toFixed(2)}`;
  guardarPedido();
}

buscar.addEventListener("input", filtrar);
categoria.addEventListener("change", filtrar);

catalogo.addEventListener("click", (evento) => {
  if (!evento.target.matches(".btn-agregar")) return;
  const id = Number(evento.target.dataset.id);
  const producto = productos.find((item) => item.id === id);
  if (!producto) return;
  const existente = pedido.find((item) => item.id === id);
  if (existente) existente.cantidad += 1;
  else pedido.push({ ...producto, cantidad: 1 });
  renderizarPedido();
});

detalle.addEventListener("click", (evento) => {
  if (!evento.target.matches(".btn-quitar")) return;
  const id = Number(evento.target.dataset.id);
  const indice = pedido.findIndex((item) => item.id === id);
  if (indice === -1) return;
  if (pedido[indice].cantidad > 1) pedido[indice].cantidad -= 1;
  else pedido.splice(indice, 1);
  renderizarPedido();
});

document.querySelector("#vaciar-pedido").addEventListener("click", () => {
  pedido = [];
  renderizarPedido();
});

entrega.addEventListener("change", () => {
  campoAula.classList.toggle("oculto", entrega.value !== "Llevar al aula");
  if (entrega.value !== "Llevar al aula") {
    document.querySelector("#aula").classList.remove("valido", "invalido");
  }
});

function validarCampo(input, expresion, errorId, mensaje) {
  const valido = expresion.test(input.value.trim());
  input.classList.toggle("valido", valido);
  input.classList.toggle("invalido", !valido);
  document.querySelector(errorId).textContent = valido ? "" : mensaje;
  return valido;
}

form.addEventListener("submit", (evento) => {
  evento.preventDefault();
  const nombre = document.querySelector("#nombre");
  const celular = document.querySelector("#celular");
  const aula = document.querySelector("#aula");
  const nombreValido = validarCampo(nombre, /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,50}$/u, "#error-nombre", "Ingrese un nombre válido.");
  const celularValido = validarCampo(celular, /^[67]\d{7}$/u, "#error-celular", "Ingrese un celular válido.");
  let aulaValida = true;
  if (entrega.value === "Llevar al aula") {
    aulaValida = validarCampo(aula, /^[A-Z]-\d{1,3}$/u, "#error-aula", "Use el formato A-101.");
  }
  const contenedorErrores = document.querySelector("#errores-generales");
  contenedorErrores.textContent = "";
  contenedorErrores.classList.remove("aviso", "aviso-error");

  if (pedido.length === 0 || !nombreValido || !celularValido || !aulaValida) {
    contenedorErrores.classList.add("aviso", "aviso-error");
    const titulo = document.createElement("strong");
    titulo.textContent = "No se pudo confirmar el pedido:";
    const lista = document.createElement("ul");
    lista.classList.add("lista-errores");
    const errores = [];
    if (pedido.length === 0) errores.push("El pedido está vacío.");
    if (!nombreValido) errores.push("Revise el nombre completo.");
    if (!celularValido) errores.push("Revise el número de celular.");
    if (!aulaValida) errores.push("Revise el aula.");
    errores.forEach((texto) => {
      const item = document.createElement("li");
      item.textContent = texto;
      lista.appendChild(item);
    });
    contenedorErrores.append(titulo, lista);
    return;
  }

  const datos = Object.fromEntries(new FormData(form));
  const total = pedido.reduce((sum, item) => sum + item.precio * item.cantidad, 0);
  const numeroPedido = Math.floor(100000 + Math.random() * 900000);
  const destino = datos.entrega === "Llevar al aula" ? `Aula ${datos.aula}` : "Recoger en caja";
  const aviso = document.createElement("div");
  aviso.classList.add("aviso");
  aviso.textContent = `Pedido N.º ${numeroPedido} confirmado para ${datos.nombre}. Total: Bs ${total.toFixed(2)}. Destino: ${destino}.`;
  contenedorErrores.appendChild(aviso);
  console.log(JSON.stringify({ numeroPedido, datos, pedido, total }));

  pedido = [];
  localStorage.removeItem("pedidoCafeteria");
  renderizarPedido();
  form.reset();
  campoAula.classList.add("oculto");
  [nombre, celular, aula].forEach((input) => input.classList.remove("valido", "invalido"));
  document.querySelector("#error-nombre").textContent = "";
  document.querySelector("#error-celular").textContent = "";
  document.querySelector("#error-aula").textContent = "";
});

document.addEventListener("DOMContentLoaded", () => {
  try {
    const guardado = JSON.parse(localStorage.getItem("pedidoCafeteria") || "[]");
    pedido = Array.isArray(guardado) ? guardado : [];
  } catch (error) {
    pedido = [];
  }
  filtrar();
  renderizarPedido();
});
