Date.prototype.Format = function (fmt)
{
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};
var table_header = [
    {"title": "域名", "data": "host"},
    {"title": "流量(Kb/s)", "data": "flux"},
    {"title": "响应时间(ms/r)", "data": "request_time"},
    {"title": "200(r/300s)", "data": "200_code"},
    {"title": "302(r/300s)", "data": "302_code"},
    {"title": "404(r/300s)", "data": "404_code"},
    {"title": "499(r/300s)", "data": "499_code"},
    {"title": "500(r/300s)", "data": "500_code"},
    {"title": "502(r/300s)", "data": "502_code"},
    {"title": "504(r/300s)", "data": "504_code"}
];

var server_table_header = [
    {"title": "服务器", "data": "sip"},
    {"title": "流量(Kb/s)", "data": "flux"},
    {"title": "响应时间(ms/r)", "data": "request_time"},
    {"title": "200(r/300s)", "data": "200_code"},
    {"title": "302(r/300s)", "data": "302_code"},
    {"title": "404(r/300s)", "data": "404_code"},
    {"title": "499(r/300s)", "data": "499_code"},
    {"title": "500(r/300s)", "data": "500_code"},
    {"title": "502(r/300s)", "data": "502_code"},
    {"title": "504(r/300s)", "data": "504_code"}
];
var server_ip_list = [
    {ip: "10.160.11.168", name: "jfz1"},
    {ip: "10.161.179.134", name: "jfz2"},
    {ip: "10.132.40.216", name: "gxq2"},
    {ip: "10.160.83.7", name: "cfg1"},
    {ip: "10.162.48.86", name: "gxq4"},
    {ip: "192.168.1.251", name: "test"}
];
var url_table_header = [
    {"title": "链接", "data": "url"},
    {"title": "流量(Kb/s)", "data": "flux"},
    {"title": "响应时间(ms/r)", "data": "request_time"},
    {"title": "200(r/300s)", "data": "200_code"},
    {"title": "302(r/300s)", "data": "302_code"},
    {"title": "404(r/300s)", "data": "404_code"},
    {"title": "499(r/300s)", "data": "499_code"},
    {"title": "500(r/300s)", "data": "500_code"},
    {"title": "502(r/300s)", "data": "502_code"},
    {"title": "504(r/300s)", "data": "504_code"}
];

var deploy_table_header = [
    {"title": "内网IP", "data": "si_local_ip"},
    {"title": "外网IP", "data": "si_remote_ip"},
    {"title": "服务器名称", "data": "si_name"},
    {"title": "服务器用途", "data": "ser_usefor"},
    {"title": "版本号", "data": "pv_v",type:"num"},
    {"title": "服务器状态", "data": "sp_status"}
];

var vcs_table_header = [
    {"title": "版本号", "data": "pv_v",type:"num"},
    {"title": "文件名", "data": "pv_name"},
    {"title": "SVNVERSION","data":'pv_svn_version'},
    {"title": "文件大小(MB)", "data": "pv_size"},
    {"title": "md5", "data": "pv_md5"},
    {"title": "mtime","data" :"pv_mtime"},
    {"title": "创建时间", "data": "pv_create_time"},
    {"title": "创建人", "data": "pv_creater"},
    {"title": "状态", "data": "pv_status"},
//    {"title": "状态说明","data":"pv_note"},
    {"title": "说明", "data": "pv_explain"}
];
var seo_table_header = [
    {"title": "链接规则", "data": "url_rule"},
    {"title": "蜘蛛爬行", "data": "spider"},
    {"title": "爬行页面数", "data": "spider_page"},
    {"title": "点击数", "data": "point"},
    {"title": "独立IP", "data": "dip"},
    {"title": "移动访问", "data": "mobile_access"},
    {"title": "外链访问", "data": "outside_access"},
    {"title": "直接访问", "data": "direct_access"},
    {"title": "搜索访问", "data": "seo_access"},
    {"title": "推广访问", "data": "sem_access"},
    {"title": "贡献下游", "data": "downstream"}
];
