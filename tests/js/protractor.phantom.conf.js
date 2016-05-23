exports.config = {
	seleniumAddress: 'http://localhost:4444/wd/hub',

	specs: ['./*.test.js'],
	capabilities: {
		'browserName': 'phantomjs',

		/*
		 * Can be used to specify the phantomjs binary path.
		 * This can generally be ommitted if you installed phantomjs globally.
		 */
		'phantomjs.binary.path': require('phantomjs-prebuilt').path,

		/*
		 * Command line args to pass to ghostdriver, phantomjs's browser driver.
		 * See https://github.com/detro/ghostdriver#faq
		 */
		'phantomjs.ghostdriver.cli.args': ['--loglevel=DEBUG']
	}
};