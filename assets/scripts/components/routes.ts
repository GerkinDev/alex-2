export const computeRouteUrl = (routeName: string, args: {[key: string]: any}) => {
	const routes = (window as any).routes as {[key: string]: string};
	if(routeName in routes){
		return routes[routeName].replace(/%7B(\w+)%7D/, (fullMatch, key) => {
			return args[key];
		});
	}
	return undefined;
}