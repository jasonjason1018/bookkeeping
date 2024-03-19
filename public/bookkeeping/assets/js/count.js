//csrf Token
var csrf = $("meta[name=csrf-token]").attr('content');

//進入頁面先抓取歸帳項目所有資料
$(document).ready(function(){
  $.ajax({
    url:"getAccountType",
    type:"POST",
    data:{},
    headers: {
        //把csrfToken放到headers
        'X-CSRF-TOKEN': csrf
    },
    success:function(resp){
        option = '<option value="0">請選擇</option>';
        for(i=0;i<resp.length;i++){
            option += '<option value="' + resp[i]['sno'] + '">' + resp[i]['type'] + '</option>'
        }
        $("#account_type").html(option);
    },
    error:function(xhr){
      console.log(xhr);
    }
  });
});

//發票類型變更後自動重新計算稅額、進項
$("#invoice_type").on('change', function(){
  money = $("#money").val();
  invoice = $(this).val();
  if(money != ''){
    untax = money;
    tax = 0;
    if(invoice == '1'){
      untax = Math.round(money/1.05);
      tax = money - untax;
    }
    $("#untax").val(untax);
    $("#tax").val(tax);
  }
});

//填入金額時自動帶入稅額、進項
$("#money").on('keyup', function(){
    invoice = $("#invoice_type").val();
    money = $(this).val();
    number = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
    var stop = 0;
    //去除數字以外的值
    money.split("").forEach(function(value, index){
        if($.inArray(value, number) == -1){
          $("#money").val($("#money").val().substring(0, $("#money").val().length -1));
          stop = 1;
        }
    });
    if(stop == 1){
      return false;
    }
    untax = money;
    tax = 0;
    if(invoice == '1'){
      untax = Math.round(money/1.05);
      tax = money - untax;
    }
    $("#untax").val(untax);
    $("#tax").val(tax);
});

//檢查發票號碼格式是否正確
$("#invoice_number").on('blur', function(){
    check_invoice_number = /^[a-zA-Z]{2}[-]{1}?[0-9]{8}$/;
    if(!check_invoice_number.test($(this).val())){
      $(this).val('');
      Swal.fire({
        icon: 'warning',
        title: '格式錯誤',
        text: '請輸入正確的發票號碼!',
        // footer: '<a href="">Why do I have this issue?</a>'
      })
    }
});

//驗證圖片格式
$('#picture').on('change', function(){
      //允許的照片格式
      allowImageType = ['jpg', 'png', 'jpeg'];
      var strFileName = $(this).val();
      if (strFileName != "") {
          var strType = strFileName.split(".");
          console.log($.inArray(strType[strType.length - 1], allowImageType));
          if ($.inArray(strType[strType.length - 1], allowImageType) == -1) {
              Swal.fire({
                icon: 'warning',
                title: '格式錯誤',
                text: '僅限上傳jpg、png、jpeg格式的圖片!',
                // footer: '<a href="">Why do I have this issue?</a>'
              })
              $(this).val('');
          }
      }
});

//若要攤分則顯示起訖日期
$("input[name=share]").on('change', function(){
    date = $("#strTr, #endTr");
    $("input[name=share]:checked").val() == 1?date.show():date.hide();
});

var i = 1;

//新增歸帳項目
function addNewAccountType(){
  accountTypeOption = $('#account_type').html();
  var html = '';
  html += '<tr id="accountTypeTr' + i + '">';
  html += '<td>歸帳項目</td>';
  html += '<td>';
  html += '<select name="account_type' + i + '" id="account_type' + i + '">';
  html += accountTypeOption;
  html += '</select>';
  html += '&thinsp;&thinsp;';
  html += '<a href="javascript:deleteAccountType(\'' + i + '\')">刪除</a>';
  html += '</td>';
  html += '</tr>';
  $('#countTable').append(html);
  i++;
}

//刪除歸帳項目
function deleteAccountType(i){
    $("#accountTypeTr" + i).replaceWith("");
}

//送出表單
$("#sendBtn").on('click', function(){
    arrData = {};
    $("#countForm").serializeArray().forEach(function(value, index){
        arrData[value.name] = value.value;
    });
    console.log(arrData);
    $.ajax({
        url:"/checkRequiredCoulmn",
        type:"POST",
        data:{arrData:arrData},
        headers: {
            //把csrfToken放到headers
            'X-CSRF-TOKEN': csrf
        },
        success:function(resp){
            if(resp['MSG'] == 0){
                message = $("#"+ resp['column']).parent().prev('td').text() + resp['msg'];
                Swal.fire({
                    icon: 'warning',
                    title: '注意',
                    text: message,
                })
                return false;
            }
            Swal.fire({
              title: '成功',
              text: "資料已送出",
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: '確定'
            }).then((result) => {
              if (result.isConfirmed) {
                submitFunc();
              }
            })
        },
        error:function(xhr){
            console.log(xhr);
        }
    });
});

//送出
function submitFunc(){
  $("#tax, #untax").attr('disabled', false);
  $("#countForm").submit();
}