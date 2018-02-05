declare global {
	namespace NodeJS {
		interface Global {
			currencyFormat: string;
		}
	}
}

const formatCurrencyMatcher = /(\d+)([\.,])(\d+)/;
const currencyComponents = global.currencyFormat.match(formatCurrencyMatcher) as string[];
const composeCurrencyText = (int: string, float: string) => `${int}${currencyComponents[3].length > 0 ? currencyComponents[2] : ''}${ (float).substr(0, (currencyComponents[3].length))}`;
export const formatCurrency = (value?: number) => {
	if(value === null){
		return global.currencyFormat.replace(
			formatCurrencyMatcher,
			composeCurrencyText('--', '--')
		);
	} else {
		const numComponents = (value + '').split('.');
		if(numComponents.length === 1){
			numComponents.push('');
		}
		numComponents[1] = numComponents[1] + '00';
		return global.currencyFormat.replace(
			formatCurrencyMatcher,
			composeCurrencyText(numComponents[0], numComponents[1])
		);
	}
}