'use strict';

/* https://github.com/angular/protractor/blob/master/docs/toc.md */

describe('my app', function () {
	beforeEach(function() {
		browser.get('http://localhost:8000/')
	});

	it('should not be redirected', function () {
		var result = browser.getLocationAbsUrl();
		expect(result).toEqual('');
	});

	it('should have autocomplete input', function() {
		browser.waitForAngular().then(function() {
			var el = element(by.id('articles_value'));
			expect(el.isPresent()).toBe(true);

			expect(el.getAttribute('placeholder')).toBe('Search articles');
		})
	});

	it('should find articles', function() {
		var el = element(by.id('articles_value'));
		el.sendKeys('man');
		browser.wait(function() {
				return element(by.id('articles_dropdown')).isDisplayed();
			}, 400).then(function() {
				el.sendKeys(protractor.Key.ARROW_DOWN, protractor.Key.ENTER);
				var title = element(by.css('article > h1'));
				expect(title.isDisplayed()).toBe(true);
				expect(title.getText()).not.toBe('');

				var author = element(by.css('article > p.article-meta > span.author'));
				expect(author.isDisplayed()).toBe(true);
				expect(author.getText()).not.toBe('');

				var dateCreate = element(by.css('article > p.article-meta > span.date-create'));
				expect(dateCreate.isDisplayed()).toBe(true);
				expect(dateCreate.getText()).not.toBe('');

				var description = element(by.css('article > p:nth-of-type(2)'));
				expect(description.isDisplayed()).toBe(true);
				expect(description.getText()).not.toBe('');

				var link = element(by.css('article > p > a'));
				expect(link.isDisplayed()).toBe(true);
				expect(link.getText()).not.toBe('');
				expect(link.getAttribute('href').toString()).not.toBe('');
			}
		);
	});
});