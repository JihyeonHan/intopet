<?php include $_SERVER["DOCUMENT_ROOT"].'/app/php/common/include/HeaderDoctype.php'; ?>
<?php include $_SERVER["DOCUMENT_ROOT"].'/app/php/lounge/include/Header.php'; ?>

<script>

fGetClientCode();


function fGetClientCode(){
    $.ajax({
            type	: 'post',
            async	: true,
            url		: 'http://total.intocns.co.kr/admin/app/mongo_interface.php',
            dataType: 'json',
            data	: {
                coCode : '489'
                , telNumber : '010-2759-2046'
                , gbn : 'clients'
                , db : 'vet'
                , collection : 'clients'
            },
            success	: function(response){
                console.log(response);
            },
            error:function(request,status,error){
                console.log('error start-----------------------');
                console.log(request)
                console.log(status)
                console.log(error)
                console.log('error end-----------------------');
            }
        });
}
    
</script>