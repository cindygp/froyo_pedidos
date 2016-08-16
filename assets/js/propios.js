$(document).ready(function () {
    setTimeout(function () {
        $("#mensaje").fadeOut(1500);
    }, 3000);
});

function eliminar(url)
{
   swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
        closeOnCancel: false
      }, 
      function(isConfirm)
      {
          if (isConfirm) {
              document.location=url;
          }else{
              swal(
                  'Cancel!',
                  'Your file is save.',
                  'success'
              );
          }
      }
  );
}

$(function() {

  // We can attach the `fileselect` event to all file inputs on the page
  $(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
  });

  // We can watch for our custom `fileselect` event like this
  $(document).ready( function() {
      $(':file').on('fileselect', function(event, numFiles, label) {

          var input = $(this).parents('.input-group').find(':text'),
              log = numFiles > 1 ? numFiles + ' files selected' : label;

          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }

      });
  });
  
});

$(document).ready(function () {
    $("#categoria").change(function ()
    {
        var dir = url;
        var catId = $(this).val();
        $.ajax({
            url: dir + "Productos_controller/obtener_subcategoria_categoria/" + catId,
            //url: "obtener_municipio_dpto/" + dptoId,
            type: "POST",
            success: function (data) {
                $("#subcategoria").html("");
                $("#subcategoria").html(data);
            }
        });
    });
});

function check_color()
{
    var color = document.getElementById("si_color").checked;
    //alert (check);
    if (color == true)
    {
        $("#color").show();
    } else
    {
        $("#color").hide();
    }
}
function check_sabor()
{
    var sabor = document.getElementById("si_sabor").checked;
    //alert (check);
    if (sabor == true)
    {
        $("#sabor").show();
    } else
    {
        $("#sabor").hide();
    }
}

$(document).ready(function () {
        $("#insertar_caracteristicas").show(function ()
        {
            $("#sabor").hide();
            $("#color").hide();
        });
    });
    
function confirmCaracteristicas()
{
    //var dir = url;
    var agree = confirm("You want to add more presentations this product");
    if (agree)
    {
       /*$.ajax({
            url: "<?php echo base_url(); ?>" + "/caracteristicas_controller/agregar_producto_caracteristicas/"+agree,
            //url: "obtener_municipio_dpto/" + dptoId,
            type: "POST"

        });*/
        //return true;
        var codigo_producto = document.getElementById("codigo_producto").value;
        var obj = {
            codigo_producto: codigo_producto,
            
        };
        sessionStorage.setItem('caratprod', JSON.stringify(obj));
    } 
    else
    {
        /*var no = 'hola';
        alert(no);*/
        $.ajax({
            url: dir + "/caracteristicas_controller/agregar_producto_caracteristicas/"+agree,
            //url: "obtener_municipio_dpto/" + dptoId,
            type: "POST"
        });
    }
}

/*function loadCaracteristicas() {
    var caratprod = JSON.parse(sessionStorage.caratprod);
    if (caratprod != null) {
        document.getElementById("codigo_producto").value = caratprod.codigo_producto;
        sessionStorage.setItem('caratprod', null);
    }
}*/