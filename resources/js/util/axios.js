import axios from "axios";
import router from "../router";
import { Notification, Message } from 'element-ui';


axios.interceptors.request.use(
    config => {
        config.headers["X-Requested-With"] = "XMLHttpRequest";
        return config;
    },
    error => {
        Promise.reject(error);
    }
);
axios.interceptors.response.use(
    ({ data }) => {
        // 对响应数据做点什么
        switch (data.code) {
            case '100':
                Message.success({
                    message: data.message
                });
                break;
            case 100:
                Message.success({
                    message: data.message
                });
                break;
            case 400:
                Message.error({
                    message: data.message
                });
                break;
            case 301:
                try {
                    data.message &&
                        Message[data.data.type]({
                            message: data.message
                        });

                    if (data.data.isVueRoute) {
                        router.replace(data.data.url);
                    } else {
                        window.location.href = data.data.url;
                    }
                } catch (error) {
                    console.error("请返回 Admin::responseRedirect()");
                }
                break;
            case 200:
                data.message &&
                    Message.success({
                        message: data.message
                    });
                break;
        }
        return data;
    },
    ({ response }) => {
        //console.log(response.status);
        // 对响应错误做点什么
        switch (response.status) {
            case 404:
                Notification.error({
                    title: "请求页面不存在",
                    message: response.data.message
                });
                router.replace('/404')
                break;
            case 419:
                Message.info({
                    message: "当前页面停留时间过长，自动刷新"
                });
                setTimeout(()=>{
                  window.location.reload()
                }, 1000)
            default:
                Notification.error({
                    title: "请求错误",
                    message: response.data.message
                });
                break;
        }

        return Promise.reject(response);
    }
);

export default axios;
