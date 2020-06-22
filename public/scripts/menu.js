window.onload(main);

var contador =1;

function main(){
    var menu = document.getElementById(".menu_bar");
    menu.addEventListener('click',function(a){

        if(contador==1){
            var nav = document.getElementById("nav").attributes([
                {left:0}
            ])
        }else{
            var nav = document.getElementById("nav").animate([
                {left:0}
            ])
        }
    });

}