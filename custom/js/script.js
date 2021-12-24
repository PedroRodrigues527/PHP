function validateform(doc){
    var name=doc;
    if (name==null || name==""){
        alert("O preenchimento do nome da unidade é obrigatório");
        return false;
    }
}