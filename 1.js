let x, y, r;
window.onload = function () {

    let buttons = document.querySelectorAll("input[name=X-button]");                              
    buttons.forEach(click);

    function click(element) {
        element.onclick = function () {
            x = Number(this.value);
            document.querySelectorAll("input[name=X-input]")[0].value=x;                        
            buttons.forEach(function (element) {
                element.style.boxShadow = "";
            });
            this.style.boxShadow = "0 0 40px 5px #6161ff";
        }
    }
}

let form = document.querySelectorAll("form[name=form]")[0];
    
function doSubmit()
{
    const date=new Date();
    document.getElementById('time').value=Math.floor(date.getTime() / 1000);
    document.getElementById('timezone').value=date.getTimezoneOffset();
    if(checkForm()){
        form.submit();
    }
    return false;
}


function validate() {
     if (!(Number.isInteger(x) && x > -5 && x < 3)) {
        createNotification("Координата Х не входит в область допустимых значений или не является целым числом");
        return false;
    }

    y = Number(document.querySelector("input[name=Y-input]").value.replace(",", "."));
    if (isNaN(y) || !((y > -5) && (y < 5)  )) {
        createNotification("Координата Y не входит в область допустимых значений");
        return false;
    }

    r = document.querySelector("input[type=radio]:checked").value;
    if(!["1","2","3","4","5"].includes(r)) createNotification("Радиус R не входит в область допустимых значений");

    return true;
}

function createNotification(text)
{
    alert(text);
}

function checkForm()
{
    if(validate())return true;
    else return false;
}
