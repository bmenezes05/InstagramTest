$(document).ready(function(){
    $("#ocultar").click(function(event){
        event.preventDefault();
        $("#enviar_foto").hide("slow");
    });

    $("#mostrar").click(function(event){
        event.preventDefault();
        $("#enviar_foto").show(1500);
    });            

    $("div.holder").jPages({
      containerID : "itemContainer",
      perPage : "6"
    });
    
    $("div.holder2").jPages({
      containerID : "itemContainer",
      perPage : "5"
    });
});
