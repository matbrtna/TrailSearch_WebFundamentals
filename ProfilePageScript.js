zmenaJmen=document.getElementById('changeNames')
zmenaHesel=document.getElementById('changePasswords')

zmenaJmen.addEventListener("submit",kontrolaDelkyJmen)
zmenaHesel.addEventListener("submit",kontrolaHesel)



function kontrolaDelkyJmen(event){
    let delkaJmena=document.getElementById("changeName").value.length
    let delkaPrijmeni=document.getElementById("changeLastName").value.length
    if(delkaJmena>16 || delkaPrijmeni>16){
        alert("Jméno a příjmení může obsahovat maximálně 16 znaků")
        event.preventDefault()
    }else if(delkaJmena===0 && delkaPrijmeni===0){
        alert("Nevyplnili jste ani jedno ze dvou polí")
        event.preventDefault()
    }
}

function kontrolaHesel(event){
    let heslo1=document.getElementById("changePasswordFirst").value
    let heslo2=document.getElementById("changePasswordSecond").value
    let delka1=document.getElementById("changePasswordFirst").value.length
    let delka2=document.getElementById("changePasswordSecond").value.length
    if(delka1<5 || delka2<5){
        alert("Heslo musí mít alespoň 5 znaků")
        event.preventDefault()
    }else if(heslo1!==heslo2){
        alert("Hesla se neshodují")
        event.preventDefault()
    }
}