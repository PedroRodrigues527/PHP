function validateValues(form)
{
    var inputs = document.getElementById(form.id).elements;
    for (let i = 0; i < inputs.length; i++) {
        valorinserido = inputs[i].value;
        nomedocampo = inputs[i].name;
        if ((valorinserido == "" || valorinserido == null) && !(nomedocampo == "email_tutor" || nomedocampo == "subitem_unit_type_name" || nomedocampo == "estado" || nomedocampo == "subitem.unit_type_id")) {
            alert("É necessário preencher todos os campos obrigatórios!");
            return false;
        }
    }
}