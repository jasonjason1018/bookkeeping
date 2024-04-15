<script src="https://cdn.bootcdn.net/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<style>
    #urls {
        width: 300px;
        height: 100px;
    }
</style>
<body>
<button id="create">生成QRCode並下載</button>
<div id="qrcodeContainer" style="display: none"></div>
<br><span id="count"></span>

<script>
    $("#create").click(() => {
        var qrcodeContainer = $("#qrcodeContainer");
        qrcodeContainer.empty();
        var serialList = [];
        let limit = 10000;
        $.ajax({
            url: `/getQrcodeSerialNumberList/${limit}`,
            type: 'GET',
            success: function (response) {
                serialList = response;
            },
            error: function (error) {
                // $("#create").click();
                console.log(error);
            }
        }).done(function () {
            console.log(serialList);

            function downloadQRCodes(index) {
                if (index >= serialList.length) return;
                $('#count').text('已下載' + (index + 1) + '張圖片');
                const value = serialList[index];
                const serial = value.serial;
                const uri = value.url;

                const qrcodeDiv = $('<div>').css({
                    margin: '10px',
                    display: 'inline-block'
                });

                const fileName = serial;

                qrcodeDiv.qrcode({
                    text: uri,
                    width: 100,
                    height: 100
                });

                qrcodeContainer.append(qrcodeDiv);

                const canvas = qrcodeDiv.find('canvas')[0];
                const imageURL = canvas.toDataURL("image/png");
                const link = document.createElement('a');
                link.href = imageURL;
                link.download = `${fileName}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(() => downloadQRCodes(index + 1), 200); // 递归下载，间隔 200ms
            }

            downloadQRCodes(0);
        });
    });


    $("#empty").click(() => {
        $("#qrcodeContainer").empty();
        $("#urls").val("");
    });
</script>
</body>
