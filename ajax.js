// This code is inspired from the excellent book
// AJAX and PHP: Building Responsive Web Applications
// Availible from Packt publishing. www.packtpub.com


var xmlHttp = createXmlHttpRequestObject();
var serverAddress = "validate_date.php";
var showErrors = true;
var queue = new Array();

function createXmlHttpRequestObject()
{
    // will store the reference to the XMLHttpRequest object
    var xmlHttp;
    // this should work for all browsers except IE6 and older
    try
    {
        // try to create XMLHttpRequest object
        xmlHttp = new XMLHttpRequest();
    }
    catch(e)
    {
        // assume IE6 or older
        var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
                "MSXML2.XMLHTTP.5.0",
                "MSXML2.XMLHTTP.4.0",
                "MSXML2.XMLHTTP.3.0",
                "MSXML2.XMLHTTP",
                "Microsoft.XMLHTTP");
        // try every id until one works
        for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
        {
            try
            {
                // try to create XMLHttpRequest object
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            }
            catch (e) {} // ignore potential error
        }
    }
    if (!xmlHttp)
        displayError("Error creating the XMLHttpRequest object.");
    else
        return xmlHttp;
}

function displayError($message)
{
    // ignore errors if showErrors is false
    if (showErrors)
    {
        // display error message
        alert("Error encountered: \n" + $message);
        // retry validation after 10 seconds
        setTimeout("ajax_validate();", 10000);
    }
}

function ajax_validate_date( dateValue, fieldID )
{
    if (xmlHttp)
    {
        dateValue = encodeURIComponent(dateValue);
        fieldID = encodeURIComponent(fieldID);

        queue.push("inputValue=" + dateValue + "&fieldID=" + fieldID);

        ajax_validate();
    }
}

function ajax_validate()
{
    if (xmlHttp)
    {
        try
        {
            // continue only if the XMLHttpRequest object isn't busy
            // and the queue is not empty
            if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
                    && queue.length > 0)
            {
                // get a new set of parameters from the queue
                var queueEntry = queue.shift();
                // make a server request to validate the extracted data
                xmlHttp.open("POST", serverAddress, true);
                xmlHttp.setRequestHeader("Content-Type",
                        "application/x-www-form-urlencoded");
                xmlHttp.onreadystatechange = handleRequestStateChange;
                xmlHttp.send(queueEntry);
            }
        }
        catch (e)
        {
            displayError(e.toString());
        }
    }
}

function handleRequestStateChange()
{
    // when readyState is 4, we read the server response
    if (xmlHttp.readyState == 4)
    {
        // continue only if HTTP status is "OK"
        if (xmlHttp.status == 200)
        {
            try
            {
                // read the response from the server
                readResponse();
            }
            catch(e)
            {
                // display error message
                displayError(e.toString());
            }
        }
        else
        {
            // display error message
            displayError(xmlHttp.statusText);
        }
    }
}

function readResponse()
{
    // retrieve the server's response
    var response = xmlHttp.responseText;
    // server error?
    if (response.indexOf("ERRNO") >= 0
            || response.indexOf("error:") >= 0
            || response.length == 0)
        throw(response.length == 0 ? "Server error." : response);
    // get response in XML format (assume the response is valid XML)
    responseXml = xmlHttp.responseXML;
    // get the document element
    xmlDoc = responseXml.documentElement;
    result = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
    fieldID = xmlDoc.getElementsByTagName("fieldid")[0].firstChild.data;
    
    icon = document.getElementById( "icon_" + fieldID )
    errDiv = document.getElementById( "error" )
    if( result == 'valid' )
    {
    	icon.innerHTML = '<img src="images/correct_32x32.png" width="16" height="16">';
    	errDiv.innerHTML = '';
    }
    else
    {
    	icon.innerHTML = '<img src="images/wrong_32x32.png" width="16" height="16" title="' + result + '">';
    	errDiv.innerHTML = '<ul><li>' + result + '</li></ul>';
    }
    
    setTimeout("ajax_validate();", 500);
}
