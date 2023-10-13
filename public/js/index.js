function sendAjaxRequest(params) {
    $.ajax(params);
}

function isUserLoggedIn() {
    const authToken = localStorage.getItem('auth-token');
    const baseUrl = window.location.origin;
    sendAjaxRequest({
        url: `${baseUrl}/api/v1/user`,
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + authToken,
        },
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            const errorResponse = error.responseText;
            const responseObject = JSON.parse(errorResponse);
            
            console.error(error.responseText);
            alert(responseObject.message);
            window.location.href = `${baseUrl}/login`;
        }
    });
}

function togglePasswordVisibility() {
    const passwordInput = $('#passwordInput');

    passwordInput.attr('type', "text");

    setTimeout(function () {
        passwordInput.attr('type', "password");
    }, 1000);
}

function setupPasswordVisibilityToggle() {
    $('#showPasswordBtn').on('click', function () {
        togglePasswordVisibility();
    });
}

function setupFormSubmit(formConfig) {
    $(formConfig.selector).on('submit', function (event) {
        event.preventDefault();
        const submittedForm = event.target;
        const formInupts = new FormData(submittedForm);

        submittedForm.reset();

        const ajaxRequestParams = {
            url: formConfig.actionUrl,
            data: formInupts,
            type: formConfig.requestType,
            processData: false,
            contentType: false,
            success: formConfig.successCallback,
            error: function (error) {
                const errorResponse = error.responseText;
                const responseObject = JSON.parse(errorResponse);
                
                console.error(error.responseText);
                alert(responseObject.message);
            },
        }

        if (formConfig.headers) {
            ajaxRequestParams['headers'] = formConfig.headers;
        }

        sendAjaxRequest(ajaxRequestParams);
    });    
}

function deleteTask(taskId) {
    const baseUrl = window.location.origin;
    const authToken = localStorage.getItem('auth-token');

    sendAjaxRequest({
        url: `${baseUrl}/api/v1/tasks/${taskId}`,
        type: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + authToken,
        },
        processData: false,
        contentType: false,
        success: function (response) {
            alert(response.message);
        },
        error: function (error) {
            const errorResponse = error.responseText;
            const responseObject = JSON.parse(errorResponse);
            
            console.error(error.responseText);
            alert(responseObject.message);
        }
    });
}