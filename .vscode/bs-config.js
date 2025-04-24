module.exports = {
  proxy: {
    target: "http://test.local/",
  },
  files: ["../**/**"],
  //reloadDelay: 100,
  injectChanges: true,
  notify: false,
  open: false,
  cors: true,
  ws: true,
};
