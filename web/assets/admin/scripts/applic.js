function incCountApplicNoProcess()
{
    $('.countApplicsNoProcess').html($('.countApplicsNoProcess').html() - 1);
}

function applicChangeStatus(applicId, statusId, statusName, oldStatusId)
{
    let sel = '#applic-'+ applicId;

    $(sel +' td.applicStatus span').css('color', getColorApplicStatus(statusId));
    $(sel +' td.applicStatus span').html(statusName);

    if (statusId === APPLIC_MAX_NUMBER_STATUS) {
        $(sel + ' .applicNextStatus').hide();
        $(sel + ' .applicSetResult').show();
    }

    if (oldStatusId === APPLIC_STATUS_NEW) {
        incCountApplicNoProcess();
    }
}

function modalInfo(title, message)
{
    $('#modalInfo').remove();

    $('body').append('<div class="modal fade" id="modalInfo">\n' +
        '    <div class="modal-dialog modal-dialog-centered" role="document">\n' +
        '        <div class="modal-content">\n' +
        '            <div class="modal-header">\n' +
        '                <h5 class="modal-title">\n' +
        '                   '+ title +' \n' +
        '                </h5>\n' +
        '                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>\n' +
        '            </div>\n' +
        '            <div class="modal-body">\n' +
        '                    '+ message +'\n' +
        '            </div>\n' +
        '            <div class="modal-footer">\n' +
        '                <input type="hidden" name="modalTransferBadClient-applic-id" value=""/>\n' +
        '                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>');

    $('#modalInfo').modal();
}

function transferBadClient(applicId) {
    $('#modalTransferBadClient #modalTransferBadClient-applic-id').html(applicId);
    $('input[name="modalTransferBadClient-applic-id"]').val(applicId);
}

function transferBadClientAction() {
    var applicId = $('input[name="modalTransferBadClient-applic-id"]').val();
    var comment = $('textarea[name="modalTransferBadClient-comment"]').val();

    $.post(ROUTE_ADMIN_VOCAB_BAD_CLIENTS_TRANSFER, {
        applicId: applicId,
        comment: comment
    }).done(function (data) {
        $('#modalTransferBadClient').modal('hide');
        if (data.success === true) {
            modalInfo('Результат перенесения в черный список', 'Клиент успешно перенесен в черный список');
        } else {
            modalInfo('Результат перенесения в черный список', 'Клиент уже есть в черном списке');
        }
    });
}

function applicNextStatus(applicId)
{
    $.post(ROUTE_ADMIN_APPLIC_NEXT_STATUS, {
        applicId: applicId
    }).done(function (data) {
        if (data.success === true) {
            applicChangeStatus(applicId, data.obj.newStatusId, data.obj.newStatus, data.obj.oldStatusId);

            modalInfo(
                'Результат перевода процедуры на следующий статус', 'Заявка успешно переведена на следующий статус. ' + '\n<br/>' +
                ' Предыдущий статус: "' + data.obj.oldStatus + '"\n <br/> \n' +
                'Новый статус: "' + data.obj.newStatus +'"'
            );
        } else {
            modalInfo('Результат перевода процедуры на следующий статус', data.msg);
        }
    });
}

function getColorApplicStatus(statusId)
{
    let color = 'black';
    if (statusId === 2) {
        color = 'green';
    } else if (statusId === 1)
    {
        color = 'orange';
    }

    return color;
}

function applicSetResultAction() {
    var applicId = $('input[name="modalApplicSetResult-applic-id"]').val();
    var comment = $('textarea[name="modalApplicSetResult-comment"]').val();

    $.post(ROUTE_ADMIN_APPLIC_SET_RESULT, {
        applicId: applicId,
        comment: comment
    }).done(function (data) {
        $('#modalApplicSetResult').modal('hide');
        if (data.success === true) {
            $('#applic-'+ applicId + ' .applicResult').html(comment);

            modalInfo('Результат подведения итогов', 'Сообщение о результатах успешно сохранено');
        } else {
            modalInfo('Результат подведения итогов', data.msg);
        }
    });
}

function applicSetResult(applicId)
{
    $('#modalApplicSetResult #modalApplicSetResult-applic-id').html(applicId);
    let comment = $('#applic-'+ applicId + ' .applicResult').html();
    if (comment !== '' && comment !== undefined) {
        $('textarea[name="modalApplicSetResult-comment"]').val(comment);
    }
    $('input[name="modalApplicSetResult-applic-id"]').val(applicId);
    $('#modalApplicSetResult').modal();
}
