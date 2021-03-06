$(document).ready(function () {

    $("#generarWord").hide();

    $('#tablaOrgaUnidad').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "lengthMenu": [[-1], ["All"]]
    });

    //Personalizar el listado de órganos
    $("#organo_chzn").css('width', '420px');
    $("#organo_chzn .chzn-drop").css('width', '410px');
    $("#organo_chzn .chzn-drop .chzn-search input").css('width', '360px');

    $("#unidad_chzn").css('width', '300px');
    $("#unidad_chzn .chzn-drop").css('width', '290px');
    $("#unidad_chzn .chzn-drop .chzn-search input").css('width', '240px');

    generarWord = function () {

        var organo = $("#organo").val();
        var unidad = $("#unidad").val();

        var nomorgano = $("#organo option:selected").text();
        var nomunidad = $("#unidad option:selected").text();

        if (organo == '' || unidad == '') {
            alert("Debe seleccionar Órgano o Unidad Orgánica");
            return false;
        }

        //Invocar ajax para generar el word
        $.ajax({
            url: urls.siteUrl + '/admin/reportes/export-word-dotacion',
            data: {
                unidad: unidad,
                nomorgano: nomorgano,
                nomunidad: nomunidad
            },
            type: 'post',
            dataType: 'json',
            success: function (result) {
                if (result.success == 1) {
                    location.href = "/Resumen-Dotacion.docx";
                }
            }
        });
    };


    $("#organo").change(function () {

        var organo = $("#organo").val();
        $("#generarWord").hide();
        if (organo == '') {
            $('#tablaOrgaUnidad').DataTable().clear().draw();
            $("#capa").html("<select id='unidad' style='width:320px'><option>[Selecione unidad orgánica]</option></select>");
            return false;
        }

        $('#tablaOrgaUnidad').DataTable().clear().draw();
        $.ajax({
            url: urls.siteUrl + '/admin/organigrama/obtener-uorganica',
            data: {
                organo: organo
            },
            type: 'post',
            //dataType: 'json',
            //Probar generando el html
            success: function (result) {
                $("#capa").html(result);
                $("#unidad").chosen();

                if ($("#unidad option").size() == 1) {
                    alert('Órgano, no tiene Unidades Orgánicas registradas.');
                    return false;
                }

                $("#unidad").change(function () {
                    var organo = $("#organo").val();
                    var unidad = $("#unidad").val();
                    var nomunidad = $("#unidad option:selected").text();
                    $('#tablaOrgaUnidad').DataTable().clear().draw();

                    if (organo == '') {
                        alert('Seleccione órgano');
                        $('#tablaOrgaUnidad').DataTable().clear().draw();
                        return false;
                    }
                    if (unidad == '') {
                        $('#tablaOrgaUnidad').DataTable().clear().draw();
                        $("#generarWord").hide();
                        return false;
                    }
                    //Buscar y pintar la tablaOrgaUnidad de los puestos obtenidos
                    $.ajax({
                        url: urls.siteUrl + '/admin/reportes/obtener-puestos-dotacion',
                        data: {
                            unidad: unidad
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (result) {

                            //Llenar tabla con los puestos
                            var contador = 0;
                            var totalQueda = 0;

                            var tcant = 0;
                            var tdotacion = 0;
                            var tqueda = 0;

                            var tdota = 0;

                            if (result == '' || result == []) {
                                alert('Unidad orgánica, no tiene puestos registrados.');
                                $('#tablaOrgaUnidad').DataTable().clear().draw();
                                return false;
                            }

                            
                            var arrayCantidad = [];
                            $.each(result, function (key, obj) {
                                contador++;    

                                var tdota = parseFloat(obj['dotacion']).toFixed(2).split(".");
                                if (parseInt(tdota[1]) >= urls.redondeo) {
                                    tdota = parseInt(tdota[0]) + 1;
                                } else {
                                    tdota = parseInt(tdota[0]);
                                }
                                tdotacion += tdota;
                                
                                //Cantidad 
                                if (arrayCantidad.indexOf(obj['puesto']) >= 0) {
                                    obj['cantidad'] = 0;
                                }
                                arrayCantidad.push(obj['puesto']);
                                
                                tcant += parseInt(obj['cantidad']);
                                totalQueda = tdota - parseInt(obj['cantidad']);

                                $('#tablaOrgaUnidad').DataTable().row.add([
                                    '<center>' + contador + "</center>",
                                    obj['puesto'],
                                    "<center>" + obj['cantidad'] + "</center>",
                                    "<center>" + tdota + "</center>",
                                    "<center>" + totalQueda + "</center>",
                                    obj['nombre_puesto']
                                ]).draw(false);
                                $("#generarWord").show();
                            });

                            //Agregando el total
                            contador++;
                            tqueda = tdotacion - tcant;
                            $('#tablaOrgaUnidad').DataTable().row.add([
                                '<center><div style="display:none">' + contador + "</div></center>",
                                '<b>Total General</b>',
                                "<center><b>" + tcant + "</b></center>",
                                "<center><b>" + tdotacion + "</b></center>",
                                "<center><b>" + tqueda + "</b></center>",
                                ''
                            ]).draw(false);
                        }
                    });
                });
            }
        });
    });
});
