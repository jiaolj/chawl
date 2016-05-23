/**
 * 获取验证码url
 * @param imgId
 * @param url
 */
function GetCheckCode(imgId, url)
{
    var now = new Date();
    var img = document.getElementById(imgId);    
    img.src = url + '?rand=' + now.getTime();
}