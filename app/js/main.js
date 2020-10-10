var user_agent = document.getElementById('user-agent'),
    mac = document.getElementById('mac'),
    device = document.getElementById('device'),
    user_id = document.getElementById('user-id'),
    date = document.getElementById('date');

if (user_agent.textContent == '___' || user_agent.textContent == '') {
    user_agent.style.width = '24%';
    mac.style.width = '24%';
    device.style.width = '24%';
    device.style.width = '24%';
    user_id.style.width = '3%';
}