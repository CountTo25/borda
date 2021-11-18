
export default class Routing {
    static go(route = '', data = {}) {
        history.pushState('', '', route);
        dispatchEvent(new PopStateEvent('popstate', {}));
        //window.location.href = window.location.href.replace(window.location.hash, '')+'#'+route;
    }

    static find(path = '', routes) {
        return routes.filter((route)=>(route.path === path))[0];
    }
}