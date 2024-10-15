"use strict";

$(document).ready(function () {
    $('#basic-datatables').DataTable({});

    $('#multi-filter-select').DataTable({
        "pageLength": 10,
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var select = $('<select class="form-control"><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>')
                });
            });
        }
    });

    // Add Row
    $('#add-row').DataTable({
        "pageLength": 10,
    });

    var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Editar"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Excluir"> <i class="fa fa-times"></i> </button> </div> </td>';

    $('#addRowButton').click(function () {
        $('#addRowModal').modal('hide');
    });
})

function notificacao(titulo, mensagem) {

    let icone = '';
    let cor = '';

    switch (titulo){
        case "Sucesso":
            icone = 'flaticon-success';
            cor = "success";
            break;
        case 'Erro':
            icone = 'flaticon-error';
            cor = "danger";
            break;
        case 'Informação':
            icone = 'flaticon-exclamation';
            cor = "info";
            break;
    }

    //Notify
    $.notify({
        icon: icone,
        title: titulo,
        message: mensagem,
    }, {
        type: cor,
        placement: {
            from: "top",
            align: "right"
        },
        time: 1500,
    });
}

function exibirAlternativas(alternativas){
    var divAlternativas = document.getElementById('alternativas');
    divAlternativas.innerHTML = '';

    for (var alternativa of alternativas) {
        var radiobox = document.createElement('input');
        radiobox.type = 'radio';
        radiobox.id = alternativa;
        radiobox.value = alternativa;

        var label = document.createElement('label')
        label.htmlFor = alternativa;

        var descricaoRadio = document.createTextNode(alternativa);
        label.appendChild(descricaoRadio);

        var quebra = document.createElement('br');

        divAlternativas.appendChild(radiobox);
        divAlternativas.appendChild(label);
        divAlternativas.appendChild(quebra);
    }
}

function configurarTipo(tipo) {
    switch (tipo) {
        case "1": //Sim/Não
            var alternativas = ['Sim', 'Não'];
            exibirAlternativas(alternativas);
            break;
        case "2": //Nota de 1 a 5
            var alternativas = ['1', '2', '3', '4', '5'];
            exibirAlternativas(alternativas);
            break;
        case "3": //Nota de 0 a 10
            var alternativas = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
            exibirAlternativas(alternativas);
            break;
    }
}
