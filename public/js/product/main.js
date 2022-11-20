$('#addInfonBtn').click(function() {
    let information = $(this).parent().parent().find('.information');
    let infoinput = `<div class="row m-0 p-0 sub-information my-1">
                            <div class="col-md-5">
                                <input type="text" placeholder="Enter Key" class="key form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Enter Value" class="value form-control">
                            </div>
                            <div class="col-md-1">
                                <i style="cursor:pointer;" class="fa-solid fa-circle-minus removeInfoInput"></i>
                            </div>
                        </div>`
    let subinfo = information.find('.sub-information');
    information.append(infoinput);

    $('.removeInfoInput').click(function() {
        $(this).parent().parent().remove();
        informationGet();
    })
    informationKeep();
});
function oldInformationGet(){
    let oldInfo = JSON.parse($('[name="information"]').val());
        if (oldInfo.length > 2) {
            infoLoop(oldInfo).then(infoAdd())
        }else{
            infoAdd();
        }
        async function infoLoop(oldInfo) {
            for (let i = 0; i < oldInfo.length-2; i++) {
                $('#addInfonBtn').click()
            }
        }
        function infoAdd() {
            let subinfo = $('.sub-information');
            subinfo.each(function(index) {
            $(this).find('.key').val(oldInfo[index].key)
            $(this).find('.value').val(oldInfo[index].value)
        })
        }
}
function informationKeep() {
    $('.sub-information input').keyup(function() {
        informationGet()
    });
}
function informationGet(){
    let value = [];
        let subinfo = $('.sub-information');
        subinfo.each(function(index) {
            let i_key = $(this).find('.key').val().toLowerCase();
            let i_value = $(this).find('.value').val()
            if (i_key.length > 0 && i_value.length > 0) {
                value.push({
                    key: i_key,
                    value: i_value
                })
            }
        })
        let data = JSON.stringify(value);
        $('[name="information"]').val(data)
}
