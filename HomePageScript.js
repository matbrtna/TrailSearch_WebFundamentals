

console.log("updated")
var regButton=document.getElementById("OtevreniReg")
var logButton=document.getElementById("OtevreniLog")
var logCloseButton=document.getElementById("ZavreniLog")
var regCloseButton=document.getElementById("ZavreniReg")
var logForm=document.querySelector("#LogDiv form")
var regForm=document.querySelector("#RegDiv form")

function otevritFormularLog() {
    document.getElementById("LogDiv").style.display = "block";
    document.getElementById("Reg_Log_Buttons").style.display = "none";
}

function zavritFormularLog() {
    document.getElementById("LogDiv").style.display = "none";
    document.getElementById("Reg_Log_Buttons").style.display = "flex"
}
function otevritFormularReg(){
    document.getElementById("RegDiv").style.display = "block";
    document.getElementById("Reg_Log_Buttons").style.display = "none";
}
function zavritFormularReg() {
    document.getElementById("RegDiv").style.display = "none";
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
        event.preventDefault();
    }
}

function KontrolaZadaniReg(event){
    const FormularReg = document.RegisterForm.elements;
    let delkaUsername = FormularReg.register_username.value.length;
    let delkaHesla =FormularReg.register_firstPassword.value.length;
    let heslo = FormularReg.register_firstPassword.value;
    let heslo_potvrzene=FormularReg.register_secondPassword.value;
    let delkaPotvrzeneho=heslo_potvrzene.length;
    let errors=[];
    if(delkaUsername<5 || delkaUsername===undefined){
        errors.push("Délka uživatélského hesla musí být aspoň 5 znaků")
        document.getElementById("regUsernameLabel").className="errorMessage"
    }else {
        document.getElementById("regUsernameLabel").className="ok"
    }
    if(delkaHesla<5 || delkaHesla===undefined ){
        errors.push("Heslo musí obshaovat aspoň 5 znaků")
        document.getElementById("regPasswordLabel").className="errorMessage"
    }else {
        document.getElementById("regPasswordLabel").className="ok"
    }
    if( heslo_potvrzene !== heslo || heslo_potvrzene===undefined || delkaPotvrzeneho<5){
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
        event.preventDefault();
    }

}




regButton.addEventListener("click",otevritFormularReg)
logButton.addEventListener("click",otevritFormularLog)
logCloseButton.addEventListener("click",zavritFormularLog)
regCloseButton.addEventListener("click",zavritFormularReg)
logForm.addEventListener("submit",KontrolaZadaniLog)
regForm.addEventListener("submit",KontrolaZadaniReg)




