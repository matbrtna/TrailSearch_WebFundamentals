


var regButton=document.getElementById("OtevreniReg")
var logButton=document.getElementById("OtevreniLog")
var logCloseButton=document.getElementById("ZavreniLog")
var regCloseButton=document.getElementById("ZavreniReg")
var logForm=document.querySelector("#LogDiv form")
var regForm=document.querySelector("#RegDiv form")





var lokaceButton = document.getElementById("ZobrazitLokaci");
var obtiznostButton = document.getElementById("ZobrazitObtiznost");

if(lokaceButton!=null) {
    lokaceButton.addEventListener("click", function () {
        ZobrazitLokaci(lokaceButton.className);
    });
}
if(obtiznostButton!=null) {
    obtiznostButton.addEventListener("click", function () {
        ZobrazitObtiznost(obtiznostButton.className);
    });
}








function otevritFormularLog() {
    document.getElementById("LogDiv").style.display = "block";
    document.getElementById("Reg_Log_Buttons").style.display = "none";
    document.getElementById("commentForm").style.display = "none";
}

function zavritFormularLog() {
    document.getElementById("LogDiv").style.display = "none";
    document.getElementById("Reg_Log_Buttons").style.display = "flex"
    document.getElementById("commentForm").style.display = "block";

}
function otevritFormularReg(){
    document.getElementById("RegDiv").style.display = "block";
    document.getElementById("Reg_Log_Buttons").style.display = "none";
    document.getElementById("commentForm").style.display = "none";

}
function zavritFormularReg() {
    document.getElementById("RegDiv").style.display = "none";
    document.getElementById("commentForm").style.display = "block";
    document.getElementById("Reg_Log_Buttons").style.display = "flex"
}



function KontrolaZadaniLog(event) {
    const FormularLog = document.LoginForm.elements;
    let DelkaJmena = FormularLog.login_username.value.length;
    let DelkaHesla = FormularLog.login_password.value.length;
    let errors = [];
    if (DelkaJmena < 5 || DelkaJmena == null) {
        errors.push("Uživatelské jméno je příliš krátké")
        document.getElementById("logUsernameLabel").className = "errorMessage"
    } else {
        document.getElementById("logUsernameLabel").className = "ok"
    }
    if (DelkaHesla < 5 || DelkaHesla == null) {
        errors.push("Heslo je příliš krátké")
        document.getElementById("logPasswordLabel").className = "errorMessage"
    } else {
        document.getElementById("logPasswordLabel").className = "ok"
    }
    if(errors.length>0){
        let ErrorMessage="";
        for(let i=0;i<errors.length;i++){
            let LastText=ErrorMessage;
            ErrorMessage=LastText+errors[i]+" "
        }
        alert(ErrorMessage);
        event.preventDefault()
    }
}

function KontrolaZadaniReg(event){
    const FormularReg = document.RegisterForm.elements;
    let DelkaUsername = FormularReg.register_username.value.length;
    let DelkaHesla =FormularReg.register_firstPassword.value.length;
    let heslo = FormularReg.register_firstPassword.value;
    let heslo_potvrzene=FormularReg.register_secondPassword.value;
    let errors=[];
    if(DelkaUsername<5 || DelkaUsername===undefined){
        errors.push("Délka uživatélského hesla musí být aspoň 5 znaků")
        document.getElementById("regUsernameLabel").className="errorMessage"
    }else {
        document.getElementById("regUsernameLabel").className="ok"
    }
    if(DelkaHesla<5 || DelkaHesla===undefined ){
        errors.push("Heslo musí obshaovat aspoň 5 znaků")
        document.getElementById("regPasswordLabel").className="errorMessage"
    }else {
        document.getElementById("regPasswordLabel").className="ok"
    }
    if( heslo !== heslo_potvrzene || heslo_potvrzene===undefined || heslo_potvrzene.length<5){
        errors.push("Hesla se neshodují")
        document.getElementById("regConfirmPasswordLabel").className="errorMessage"
    }else{
        document.getElementById("regConfirmPasswordLabel").className="ok"
    }
    if(errors.length>0){
        let ErrorMessage="";
        for(let i=0;i<errors.length;i++){
            let LastText=ErrorMessage;
            ErrorMessage=LastText+errors[i]+" "
        }
        alert(ErrorMessage);
        event.preventDefault()
    }

}





function ZobrazitLokaci(location) {
    let text =document.getElementById("TrailLokace")
    let googleText =document.getElementById("GoogleLokace")
    if (text.className === "Opened") {
        text.textContent="";
        text.className="Closed"
        googleText.className="Closed"
    }else{
        SendRequest(location,"TrailLocations");
        text.className="Opened"
        googleText.className="Opened"
    }
}

function ZobrazitObtiznost(location){
    let text =document.getElementById("TrailObtiznost")
    if (text.className === "Opened") {
        text.className="Closed"
    }else {
        SendRequest(location,"TrailComplexity");
        text.className="Opened"
    }
}


function SendRequest(location,folder){
    var request=new XMLHttpRequest();
    var adress="https://zwa.toad.cz/~brtnamat/"+folder+"/"+location+".txt";
    request.open("GET", adress, true);
    request.send();
    if(folder==="TrailLocations"){
        request.addEventListener("load", ExecuteLocationResponse)
    }
    else if(folder==="TrailComplexity") {
        request.addEventListener("load", ExecuteComplexityResponse)
    }
}

function ExecuteLocationResponse(event){
    let request = event.target
    let text = request.responseText.split('\n')
    var textToChange=document.getElementById("TrailLokace");
    var googleTextToChange=document.getElementById("GoogleLokace");
    textToChange.textContent=text[0];
    var newHref=text[1];
    googleTextToChange.href=newHref;
}

function ExecuteComplexityResponse(event) {
    let request = event.target
    let text = request.responseText.split('\n')
    let textToChange = document.getElementById("TrailObtiznost")
    // let lineBreak = document.createElement("br");

    for (let i = 0; i < text.length; i++) {
        let existingText = textToChange.textContent
        textToChange.textContent = existingText + text[i];
        textToChange.innerHTML = text.join("<br>");
    }


}

if(regButton!=null) {
    regButton.addEventListener("click", otevritFormularReg)
}
if(logButton!=null) {
    logButton.addEventListener("click", otevritFormularLog)
}
logCloseButton.addEventListener("click",zavritFormularLog)
if(regCloseButton!=null) {
    regCloseButton.addEventListener("click", zavritFormularReg)
}
if(logForm!=null) {
    logForm.addEventListener("submit", KontrolaZadaniLog)
}
if(regForm!=null){
regForm.addEventListener("submit",KontrolaZadaniReg)
}















