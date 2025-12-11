let prodructos_venta = [];
let id = 2;
let id2 = 4;
let producto = {};
    producto.nombre = "Producto A";
    producto.precio = 100;
    producto.cantidad = 2;

let producto2 = {};
    producto2.nombre = "Producto B";
    producto2.precio = 200;
    producto2.cantidad = 1;
    //prodructos_venta.push(producto);


prodructos_venta.id=producto;
prodructos_venta[id2]=producto2;
console.log(prodructos_venta);

async function agregar_producto_temporal(){
    let id = document.getElementById('id_producto_venta').value;
    let precio = document.getElementById('producto_precio_venta').value;
    let cantidad = document.getElementById('producto_cantidad_venta').value;
    const datos = new FormData();
    datos.append('id_producto', id);
    datos.append('precio', precio);
    datos.append('cantidad', cantidad);
    try{
    let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrarTemporal', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (json.status) {
            if (json.msg=="registrado") {
                alert("el producto fue registrado");
            }else{
                alert("el producto fue actualizado");
            }
        }
    } catch (error){
console.log("error en agregar producto temporal " + error);
}

async function listar_temporales() {
     try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=listar_venta_temporal', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
         
        });
        json = await respuesta.json();
        if (json.status) {
          json.data.forEach(t_venta => {
            lista_temporal +=`<tr>
                    <td><input type="number" id="cant_${t_venta.id}" values="${t_venta.nombre}" style="width: 60px;" onkeyup="actualizar_subtotal(${t_venta.id});" onchange="actualizar_subtotal(${t_venta.id}), ${t_venta.precio} ;"></td>
                    <td>s/. ${t_venta.venta.precio}</td>
                    <td> id="subtotal_${venta.cantidad*t_venta.precio}</td>
                    <td><button class="btn btn-danger btn-sm">Eliminar</input</td>
                  </tr>`
          });
        document.getElementById('lista_compra').innerHTML = lista_temporal;
        }
    } catch (error) {
        console.log("error al cargar productos temporales " + error);
    }
}
}

async function actualizar_subtotal(id, precio) {
    let cantidad = document.getElementById('cant_'+ id).value;
    try {
    const datos = new FormData();
    datos.append('id', id);
    datos.append('cantidad', cantidad);
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=actualizar_cantidad', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (json.status) {
            sudtotal = cantidad*precio;
            document.getElementById('subtotal_'+id).innerHTML = 'S/.'+subtotal;
        }
    } catch (error) {
        console.log("error al actualizar catidad : " + error);
    }
}