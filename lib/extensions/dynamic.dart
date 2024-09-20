import 'dart:math';

extension DynamicParsing on dynamic {
  double toDouble() {
    return double.parse(this);
  }

  //
  String fill(List values) {
    //
    String data = this.toString();
    for (var value in values) {
      data = data.replaceFirst("%s", value.toString());
    }
    return data;
  }

  //randomAlphaNumeric(length)
  String randomAlphaNumeric(int length) {
    final String chars =
        "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    final Random rnd = Random(DateTime.now().millisecondsSinceEpoch);
    final String result = String.fromCharCodes(
      Iterable.generate(
        length,
        (_) => chars.codeUnitAt(rnd.nextInt(chars.length)),
      ),
    );
    return result;
  }

  //generate percentage of this, if not double, try to parse it, if not possible, return 0
  double percentage(double percent) {
    try {
      double value = double.parse(this.toString());
      return value * (percent / 100);
    } catch (e) {
      return 0;
    }
  }
}
