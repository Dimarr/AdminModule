/**
 * Created by РумянцевДмитрий on 15.01.2018.
 */
function edit_row(id)
{
    var fname=document.getElementById("fname_val"+id).innerHTML;
    var lname=document.getElementById("lname_val"+id).innerHTML;
    var phone=document.getElementById("phone_val"+id).innerHTML;
    var email=document.getElementById("email_val"+id).innerHTML;
    var carplate=document.getElementById("car_plate"+id).innerHTML;
    //var detail=document.getElementById("detail_val"+id).innerHTML;

    //window.alert(fname);

    document.getElementById("fname_val"+id).innerHTML="<input type='text' id='fname_text"+id+"' value='"+fname+"'>";
    document.getElementById("lname_val"+id).innerHTML="<input type='text' id='lname_text"+id+"' value='"+lname+"'>";
    document.getElementById("phone_val"+id).innerHTML="<input type='text' id='phone_text"+id+"' value='"+phone+"'>";
    document.getElementById("email_val"+id).innerHTML="<input type='text' id='email_text"+id+"' value='"+email+"'>";
    document.getElementById("car_val"+id).innerHTML="<input type='text' id='car_text"+id+"' value='"+carplate+"'>";
   // document.getElementById("detail_val"+id).innerHTML="<input type='text' id='detail_text"+id+"' value='"+detail+"'>";

    document.getElementById("edit_button"+id).style.display="none";
    document.getElementById("show_button"+id).style.display="none";
    document.getElementById("showcarpic_button"+id).style.display="none";
    document.getElementById("showp_buttonuser"+id).style.display="none";
    document.getElementById("save_button"+id).style.display="inline";
}

function save_row(id)
{
    var fname=document.getElementById("fname_text"+id).value;
    var lname=document.getElementById("lname_text"+id).value;
    var email=document.getElementById("email_text"+id).value;
    var phone=document.getElementById("phone_text"+id).value;
    var car=document.getElementById("car_text"+id).value;
    //var detail=document.getElementById("detail_text"+id).value;

    $.ajax
    ({
        type:"POST",
        url:"./records.php",
        data:{
            edit_row:"edit_row",
            row_id:id,
            fname_val:fname,
            lname_val:lname,
            email_val:email,
            //detail_val:detail,
            car_val:car,
            phone_val:phone
        },
        success: function(result)  {
            document.getElementById("fname_val"+id).innerHTML=fname;
            document.getElementById("lname_val"+id).innerHTML=lname;
            document.getElementById("email_val"+id).innerHTML=email;
            //document.getElementById("detail_val"+id).innerHTML=detail;
            document.getElementById("car_val"+id).innerHTML=car;
            document.getElementById("phone_val"+id).innerHTML=phone;
            document.getElementById("edit_button"+id).style.display="inline";
            document.getElementById("save_button"+id).style.display="none";
            document.getElementById("show_button"+id).style.display="inline";
            document.getElementById("showp_buttonuser"+id).style.display="inline";
            document.getElementById("showcarpic_button"+id).style.display="inline";
            location.reload();
        }
    });
}

function edit_row_sp(id)
{
    var name=document.getElementById("name_val"+id).innerHTML;
    var phone=document.getElementById("phone_val"+id).innerHTML;
    var email=document.getElementById("email_val"+id).innerHTML;
    var carplate=document.getElementById("car_plate"+id).innerHTML;
    //var detail=document.getElementById("detail_val"+id).innerHTML;

    //window.alert(fname);

    document.getElementById("name_val"+id).innerHTML="<input type='text' id='name_text"+id+"' value='"+name+"'>";
    document.getElementById("phone_val"+id).innerHTML="<input type='text' id='phone_text"+id+"' value='"+phone+"'>";
    document.getElementById("email_val"+id).innerHTML="<input type='text' id='email_text"+id+"' value='"+email+"'>";
    document.getElementById("car_val"+id).innerHTML="<input type='text' id='car_text"+id+"' value='"+carplate+"'>";
    // document.getElementById("detail_val"+id).innerHTML="<input type='text' id='detail_text"+id+"' value='"+detail+"'>";

    document.getElementById("edit_button"+id).style.display="none";
    document.getElementById("show_button"+id).style.display="none";
    document.getElementById("showp_button"+id).style.display="none";
    document.getElementById("show_pic"+id).style.display="none";
    document.getElementById("showcarpic_button"+id).style.display="none";
    document.getElementById("save_button"+id).style.display="inline";
}

function save_row_sp(id)
{
    var name=document.getElementById("name_text"+id).value;
    var email=document.getElementById("email_text"+id).value;
    var phone=document.getElementById("phone_text"+id).value;
    var car=document.getElementById("car_text"+id).value;
    //var detail=document.getElementById("detail_text"+id).value;
    //alert(carbrand+","+carmodel)
    $.ajax
    ({
        type:"POST",
        url:"./records.php",
        data:{
            edit_row_sp:"edit_row_sp",
            row_id:id,
            name_val:name,
            email_val:email,
            //detail_val:detail,
            car_val:car,
            phone_val:phone
        },
        success: function(result)  {
            document.getElementById("name_val"+id).innerHTML=name;
            document.getElementById("email_val"+id).innerHTML=email;
            //document.getElementById("detail_val"+id).innerHTML=detail;
            document.getElementById("car_val"+id).innerHTML=car;
            document.getElementById("phone_val"+id).innerHTML=phone;
            document.getElementById("edit_button"+id).style.display="inline";
            document.getElementById("save_button"+id).style.display="none";
            document.getElementById("show_button"+id).style.display="inline";
            document.getElementById("showp_button"+id).style.display="inline";
            document.getElementById("show_pic"+id).style.display="inline";
            document.getElementById("showcarpic_button"+id).style.display="inline";
            location.reload();
        }
    });
}


function search(type) {
    var email=document.getElementById("Email").value;
    var phone=document.getElementById("Phone").value;
    if (type=='sps') {
        var paymestatus = document.getElementById("paymestatus").value;
    }
    //alert('loikjjjiojioj');
    var crit1="";
    var crit2="";
    var crit3="";
    if (paymestatus>-1) {
        crit3="&pstatus="+paymestatus;
    }
    if (email.trim()!=="") {
        crit1 = "email="+email.trim();
    }
    if (phone.trim()!=="") {
        crit2 = "phone="+phone.trim();
    }
    if (crit1==="") {
        if (crit2!=="") {
            document.location.assign("./"+type.trim()+".php?" + crit2 + crit3 );
        } else {
            if (crit3 ==="") {
                document.location.assign("./" + type.trim() + ".php");
            } else {
                document.location.assign("./" + type.trim() + ".php?"+ crit3.slice(1));
            }
        }
    }  else {
        if (crit2!=="") {
            document.location.assign("./"+type.trim()+".php?" + crit1+"&"+crit2 + crit3);
        } else {
            document.location.assign("./"+type.trim()+".php?" + crit1 + crit3);
        }
    }
}

function appr_rej(id,type,currentst,spid,amnt,instl) {
    var newst = 0;
    //alert("amount" +amnt+" installments "+instl);
    if (currentst.trim()==="1" || currentst.trim()==="2" || currentst.trim()==="4") {
        if (type==="approve") {
                switch (currentst.trim()){
                    case "1" : newst=2; // Accepted
                        break;
                    case "2" : newst=4; // approve by CC
                        break;
                    case "4" :
                        var famount = amnt;
                        famount = prompt("Enter final amount:",amnt);
                        var ins = instl;
                        ins = prompt("Enter final number of installments:",instl);
                        newst=10; // approve by CC
                        break;
                }
                //newst = 10;
        } else {
                newst = 7; //reject by CC
        }
    } else {
        alert("This Status could not be changed");
    }

    $.ajax
    ({
        type:'POST',
        url:'./records.php',
        data:{
            changestatuscall:'changestatuscall',
            row_id:id,
            status:newst,
            spid : spid,
            inst : ins,
            famount : famount
        },
        success: function(result)  {
            //alert(result);
            if (result.trim() !== '') {
                var res = JSON.parse(result);
                if (res.status_code===1)
                    alert("Something was wrong. Error details: "+res.status_error_details+" "+res.status_additional_info);
                else
                    alert("Payment amount "+res.payme_transaction_total+" NIS. Status of payment: "+res.basic_sale_status+". Payment date: "+res.sale_paid_date);
            }
            if (newst>0) window.location.reload();
        }
    });

}

function showcarpic_user(pic) {
    if (pic.trim().indexOf("://")>0) {
        var w = window.open(pic, "Picture of user's car", "resizable=yes,scrollbars=yes");
    } else {
        alert("The picture was not uploaded");
    }
}

function showcarpic_sp(pic) {
    if (pic.trim().indexOf("://")>0) {
        var w = window.open(pic, "Picture of user's car", "resizable=yes,scrollbars=yes");
    } else {
        alert("The picture was not uploaded");
    }
}

function showpic_sp(pic) {
    if (pic.trim().indexOf("://")>0) {
        var w = window.open(pic, "Picture of SP", "resizable=yes,scrollbars=yes");
    } else {
        alert("The picture was not uploaded");
    }
}

function show_fees(id,amount,errortext, paymeslavecode) {
   // var leftpos = window.event.clientX+50;
        //document.getElementById("fee_button" + id).clientLeft+100;
    //var toppos = window.event.clientY+50;
    //document.getElementById("fee_button" + id).clientTop;
    //alert(id);
    //var w = window.open("./fees.php?saleid=" + id+ "#openModal", "Fee's details", "resizable=no,scrollbars=no,left="+leftpos+",top="+toppos+",width=420,height=230");
    //alert(amount);
    $.ajax
    ({
        type:'POST',
        url:'./paymegetdata.php',
        data:{
            sale_id:id,
            our_amount : amount,
            err_text : errortext,
            slavecode : paymeslavecode
        },
        success: function(result)  {
            //alert(result);
            //var w =showModalDialog("./fees.php?"+result);
            var w = window.open("./fees.php?"+result,"Fees", "resizable=no,scrollbars=no,left=300,top=100,height=400,width=150");
        /*    document.getElementById("log_val"+id).innerHTML=status;
            if (status===1)
            {
                document.getElementById("login_val"+id).innerHTML="Online";
            } else {
                document.getElementById("login_val"+id).innerHTML="Offline";
            }  */
        }
    });

}

function all_payments(sp,id) {
    document.location.assign("./payments.php?sp="+sp.trim()+"&row_id="+id.trim());
}

function search_payments(paymeid) {
    var saleid=document.getElementById("salecode").value;
    //alert(saleid);
    $.ajax
    ({
        type:'POST',
        url:'./paymegetdata.php',
        data:{
            payme_seller_id:paymeid,
            sale_code : saleid
        },
        success: function(result)  {
            //alert(result);
            if (result.trim()!=="") {
                document.location.assign(document.location.href+"&saleid="+result.trim());
            } else {
                alert("Payment #"+saleid+" not found");
            }
            //var w =showModalDialog("./fees.php?"+result);
            //var w = window.open("./fees.php?"+result,"Fees", "resizable=no,scrollbars=no,left=300,top=100,height=400,width=150");
            /*    document.getElementById("log_val"+id).innerHTML=status;
             if (status===1)
             {
             document.getElementById("login_val"+id).innerHTML="Online";
             } else {
             document.getElementById("login_val"+id).innerHTML="Offline";
             }  */
        }
    });
}

function showcalls_sp(id) {
    var w = window.open("./callssp.php?row_id=" + id, "List of requests", "left=1,top=1,resizable=yes,scrollbars=yes");
}

function showpayments_sp(id) {
    var w = window.open("./payments.php?sp=1&row_id=" + id, "List of payments", "resizable=yes,scrollbars=yes");
}

function showpayments_user(id) {
    var w = window.open("./payments.php?sp=0&row_id=" + id, "List of payments", "resizable=yes,scrollbars=yes");
}

function online_sps() {
    var w = window.open("./mapsps.php", "Online Service Providers", "resizable=yes,scrollbars=yes");
}

function pairsshow() {
    //var callstatus = document.getElementById("callstatus").value
    var w = window.open("./mapsps_users.php", "User-SP accepted calls", "resizable=yes,scrollbars=yes");
}

function showcalls(id)
{
    var w=window.open("./calls.php?row_id="+id, "List of requests", "left=1,top=1,resizable=yes,scrollbars=yes");
    //w.focus();
    //w.document.write('<!DOCTYPE html><title>Calls</title><p>List of calls<button onclick="window.close();">Close</button>');
    //w.document.body.innerHTML="./calls.php"
    /*$.ajax
    ({
        type:"GET",
        url:"./calls.php",
        data:{
            showcalls:"showcalls",
            row_id:id,
        },
        success:function(result) {
            w.document.write('<tr><th>Date of request</th></tr>');
            //$('div').html(data);
            $(".data").html(result);
            //w.document.getElementById("row"+id).innerHTML=;
        }
    });*/
}
function logout() {
    $.ajax
    ({
        url: './ajax.php',
    });
}

function changestatus(id)
{
    var status=document.getElementById("log_val"+id).innerHTML;
    status=1-status;
    $.ajax
    ({
        type:'POST',
        url:'./records.php',
        data:{
            changestatus:'changestatus',
            row_id:id,
            status:status
        },
        success: function(result)  {
            document.getElementById("log_val"+id).innerHTML=status;
            if (status===1)
            {
                document.getElementById("login_val"+id).innerHTML="Online";
            } else {
                document.getElementById("login_val"+id).innerHTML="Offline";
            }
        }
    });
}

function changestatus_sp(id)
{
    var status=document.getElementById("log_val"+id).innerHTML;
    status=1-status;
    $.ajax
    ({
        type:'POST',
        url:'./records.php',
        data:{
            changestatussp:'changestatus_sp',
            row_id:id,
            status:status
        },
        success: function(result)  {
            document.getElementById("log_val"+id).innerHTML=status;
            if (status===1)
            {
                document.getElementById("login_val"+id).innerHTML="Online";
            } else {
                document.getElementById("login_val"+id).innerHTML="Offline";
            }
        }
    });
}

function changebusystatus_sp(id)
{
    var status=document.getElementById("bus_val"+id).innerHTML;
    status=1-status;
    $.ajax
    ({
        type:'POST',
        url:'./records.php',
        data:{
            changebusystatussp:'changebusystatus_sp',
            row_id:id,
            status:status
        },
        success: function(result)  {
            document.getElementById("bus_val"+id).innerHTML=status;
            if (status===1)
            {
                document.getElementById("busy_val"+id).innerHTML="Busy";
            } else {
                document.getElementById("busy_val"+id).innerHTML="Available";
            }
        }
    });
}


function comboInit(thelist)
{
    theinput = document.getElementById(theinput);
    var idx = thelist.selectedIndex;
    var idd=thelist.options[idx].id;
        //if(theinput.value == "")
    theinput.value = idd;
}

function combo(thelist, theinput)
{
    theinput = document.getElementById(theinput);
    var idx = thelist.selectedIndex;
    var idd=thelist.options[idx].id;
    var content = thelist.options[idx].innerHTML;
    theinput.value = idd; //content;
}

function filter() {
    var idd = document.getElementById("theinput").value;
    var callid = parseInt(document.getElementById("number").value,10);
    if (idd.trim()==="") idd=0;
    //alert(idd);
    var s=document.location.href;
    if (callid>0) {
        if (s.indexOf("&") < 0) {
            document.location.assign(s + "&callid=" + callid);
        } else {
            document.location.assign(s.substring(0, s.indexOf("&")) + "&callid=" + callid);
        }
    } else {
        if (idd > 0) {
            if (s.indexOf("&") < 0) {
                document.location.assign(s + "&status=" + idd);
            } else {
                document.location.assign(s.substring(0, s.indexOf("&")) + "&status=" + idd);
            }
        } else {
            if (s.indexOf("&") < 0) {
                document.location.assign(s);
            } else {
                document.location.assign(s.substring(0, s.indexOf("&")));
            }
        }
    }
}
function map(id, tuser,tsp) {
    $.ajax
    ({
        type:'POST',
        url:'./records.php',
        data:{
            map:'map',
            row_id:id
        },
        success: function(result)  {
            var res = JSON.parse(result);
            //alert(res.X1);
            var w = window.open("./map.php?X1="+res.X1+"&Y1="+res.Y1+"&X2="+res.X2+"&Y2="+res.Y2+"&tuser='"+tuser+"'&tsp='"+tsp+"'","Map", "resizable=yes,scrollbars=yes");
        }
    });
}