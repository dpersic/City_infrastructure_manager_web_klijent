 $(window).on("load",function(){
          $(".loader-wrapper").fadeOut("slow");
        });


function Login()
{
   var Username= $('#korisnicko_ime').val();
   var Password= $('#lozinka').val();
   console.log(Username);
		$.ajax({
        type: "POST",
        url: 'login.php',
        data: {'sUsername':Username,'sPassword':Password, 'action_id': 'login'},
        success: function (oData)
        {
        console.log(oData.length);

        data = JSON.parse(oData);
        korisniciList = [];
            data.forEach(function(users)
            {
                var object = {
                    name: users.korisnicko_ime,                 
                    password: users.lozinka,
                    ime: users.ime,
                    prezime:users.prezime,
                    id_korisnik:users.id_korisnik
                    
                };
                korisniciList.push(object);
            });

            var length=korisniciList.length;
          if(length == 1 )
          {
           localStorage.setItem('id_korisnik', korisniciList[0]['id_korisnik']);
          window.open('admin_view.php', '_self');
            
          }
          else
          {
              alert("Niste unijeli točne podatke");
          }


          
        },
        error: function (XMLHttpRequest, textStatus, exception) {
            console.log("Ajax failure\n");
        },
        async: true
    });
}
 

function Logout()
{
          
    $.ajax({
        type: "POST",
        url: 'login.php',
        data: {'action_id': 'logout'},
        success: function (oData)
        {
            bootbox.alert({
               size: "small",
               title: "Odjava",
               message: "Uspješno ste se odjavili!",
               action:window.localStorage.clear()
          })
            
            window.location.replace('sing_out.php', '_self'); 
        },
        error: function (XMLHttpRequest, textStatus, exception) {
            console.log("Ajax failure\n");
        },
        async: true
    });
 }

 function Guest()
        {
                window.open('user_view.php','_self');
        }
			    
	


