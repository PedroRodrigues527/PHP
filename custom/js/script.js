function validateValues(form)
{
    var inputs = document.getElementById(form.id).elements; //especificar seus elementos (tudo)
    for (let i = 0; i < inputs.length; i++) {
        valorinserido = inputs[i].value;//input do usuário
        nomedocampo = inputs[i].name;//dn_crianca
        //Se input vazio/null e nome dos campos não especificados no if, devolve false + alert ex: nc_tutor, nc_crianca, dn_crianca -> gestao de registos
        if ((valorinserido == "" || valorinserido == null) && !(nomedocampo == "email_tutor" || nomedocampo == "subitem_unit_type_name" || nomedocampo == "estado" || nomedocampo == "subitem.unit_type_id")) {
            alert("É necessário preencher todos os campos obrigatórios!");
            return false;
        }
    }
}