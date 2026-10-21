module.exports = {
  extends: ['@commitlint/config-conventional'],
  rules: {
    'type-enum': [
      2,
      'always',
      ['feat', 'fix', 'docs', 'test', 'refactor', 'style', 'perf', 'chore', 'revert', 'ci']
    ],
    'type-case': [2, 'always', 'lower-case'],
    'type-empty': [2, 'never'],
    'scope-empty': [2, 'never'],
    'scope-case': [2, 'always', 'lower-case'],
    'subject-empty': [2, 'never'],
    'subject-case': [2, 'always', 'sentence-case'],
    'subject-full-stop': [2, 'never', '.'],
    'header-max-length': [2, 'always', 72],
    'body-max-line-length': [2, 'always', 100],
    'footer-max-line-length': [2, 'always', 100],
  },
  prompt: {
    messages: {
      type: "Pilih tipe perubahan:",
      scope: "Kode WBS (contoh: 3.4.1):",
      subject: "Ringkasan singkat (imperative, max 72 char):",
      body: "Penjelasan detail (opsional):",
      footer: "Footer (Refs: WBS x.x.x, R-xx):",
    },
  },
};