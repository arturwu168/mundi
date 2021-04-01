'ucss strict';

//虛擬名稱
const gVweb1='';
const gVweb2 = new RegExp(gVweb1, 'g');

// 站台網址
const gMweb1 = window.location.origin+gVweb1;

// 站台全網址
const gNweb1 = window.location.href.substr(0, (window.location.href.length-1));

// 站台網址路徑
const gPweb1 = window.location.pathname.substr(0, (window.location.pathname.length-1)).replace(gVweb2, '');

// API程序-主機
const gAweb1 = gMweb1+'';

// 上傳檔案-主機
const gUweb1 = gMweb1+'';

// 資源-主機
const gRweb1 = gMweb1+'';

