var x = 65;
var q = 5;
var f = 0;

function changewidth(){
 if(x>100&&f==0){f=1;return;}
 if(x<87&&f==1){f=0;return;}
 if(f)q=-5;if(!f)q=5;x=x+q;
 e=document.getElementById("body");
 e.style.width = x + '%';
  t=setTimeout("changewidth();",0);
}