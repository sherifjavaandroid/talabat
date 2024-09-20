extension StringParsing on String {
  //

  String telFormat() {
    return this.replaceAll(new RegExp(r'^0+(?=.)'), '');
  }

  bool get isNotDefaultImage {
    return !this.toString().contains("default");
  }
}
