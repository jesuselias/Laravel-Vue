module.exports = {
  testEnvironment: 'jsdom',
  transform: {

    '^.+\\.js$': 'babel-jest'
  },
  moduleFileExtensions: ['js', 'vue', 'json'],
  roots: ['<rootDir>/tests'],
  collectCoverageFrom: ['**/*.{js,vue}', '!**/node_modules/**'],
  globals: {
    'vue-jest': {
      pug: {
        doctype: 'html,vue'
      }
    }
  }
};