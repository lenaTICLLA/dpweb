document.addEventListener('DOMContentLoaded', ()=>{cargarProductos();});

async function cargarProductos(){
    try{
        const url = base_url+'control/ProductoController.php?tipo=ver_productos';
        const resp = await fetch(url,{method:'POST', headers:{'Accept':'application/json'}});
        if(!resp.ok) throw new Error(resp.status);
        const json = await resp.json();
        const productos = Array.isArray(json)?json:(json.data && Array.isArray(json.data)?json.data:[]);
        cargarCarousel(productos.slice(0,3));
        renderProductos(productos);
    }catch(e){
        console.error(e);
        document.getElementById('productos-container').innerHTML='<div class="col-12 text-center text-danger">Error al cargar productos.</div>';
    }
}

function renderProductos(productos){
    const container = document.getElementById('productos-container');
    if(!productos || productos.length===0){container.innerHTML='<div class="col-12 text-center">No hay productos disponibles.</div>'; return;}
    container.innerHTML='';
    productos.forEach(p=>{
        const id = p.id??p.producto_id??'';
        const nombre = p.nombre??'Sin nombre';
        const cat = p.categoria??'Sin categoría';
        const precio = Number(p.precio??0).toFixed(2);
        const img = p.imagen?base_url+p.imagen:'https://via.placeholder.com/300x250?text=Sin+Imagen';
        const col = document.createElement('div'); col.className='col-xl-3 col-lg-4 col-md-6 col-sm-6';
        col.innerHTML=`
            <div class="producto-card" data-id="${id}">
                <img src="${img}" alt="${nombre}" onerror="this.src='https://via.placeholder.com/300x250?text=Imagen+No+Disponible'">
                <div class="producto-info">
                    <div class="producto-nombre">${nombre}</div>
                    <div class="producto-categoria">${cat}</div>
                    <div class="producto-precio">S/ ${precio}</div>
                    <div class="botones">
                        <button class="btn-ver-detalles btn btn-sm" data-id="${id}"><i class="bi bi-eye"></i> Ver</button>
                        <button class="btn-anadir-carrito btn btn-sm" data-id="${id}"><i class="bi bi-cart-plus"></i> Añadir</button>
                    </div>
                </div>
            </div>`;
        container.appendChild(col);
    });

    container.onclick = e=>{
        const ver = e.target.closest('.btn-ver-detalles');
        const add = e.target.closest('.btn-anadir-carrito');
        if(ver){verDetalles(ver.dataset.id);}
        if(add){añadirAlCarrito(add.dataset.id);}
    };
}

function cargarCarousel(productos){
    const inner = document.querySelector('#productos-carousel .carousel-inner');
    const indicators = document.querySelector('#productos-carousel .carousel-indicators');
    if(!inner || !indicators) return;
    inner.innerHTML=''; indicators.innerHTML='';
    if(!productos || productos.length===0){
        inner.innerHTML=`<div class="carousel-item active"><img src="https://via.placeholder.com/800x400?text=Sin+Productos" class="d-block w-100"></div>`;
        return;
    }
    productos.forEach((p,i)=>{
        const id=p.id??p.producto_id??i;
        const nombre=p.nombre??'Sin nombre';
        const cat=p.categoria??'Sin categoría';
        const precio=Number(p.precio??0).toFixed(2);
        const img=p.imagen?base_url+p.imagen:'https://via.placeholder.com/800x400?text=Sin+Imagen';

        const ind = document.createElement('button');
        ind.type='button'; ind.setAttribute('data-bs-target','#productos-carousel');
        ind.setAttribute('data-bs-slide-to',i); ind.setAttribute('aria-label',`Slide ${i+1}`);
        if(i===0){ind.className='active'; ind.setAttribute('aria-current','true');}
        indicators.appendChild(ind);

        const item = document.createElement('div');
        item.className='carousel-item'+(i===0?' active':'');
        item.innerHTML=`<img src="${img}" class="d-block w-100" alt="${nombre}">`;
        inner.appendChild(item);
    });
}

/* Funciones placeholder */
function verDetalles(id){ if(!id) return alert('ID no encontrado'); window.location.href=base_url+'producto/'+id; }
function añadirAlCarrito(id){ if(!id) return alert('ID no encontrado'); console.log('Añadir al carrito',id); mostrarToast('Producto añadido al carrito');}
function mostrarToast(msg){
    let t=document.getElementById('small-toast');
    if(!t){t=document.createElement('div'); t.id='small-toast'; t.style.cssText='position:fixed;bottom:20px;right:20px;padding:10px 14px;background:rgba(0,0,0,0.7);color:#fff;border-radius:10px;z-index:9999;'; document.body.appendChild(t);}
    t.textContent=msg; t.style.opacity='1'; setTimeout(()=>{t.style.opacity='0';},1800);
}
