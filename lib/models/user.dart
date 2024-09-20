class User {
  int id;

  String name;
  String? code;
  String email;
  String phone;
  String? rawPhone;
  String? countryCode;
  String photo;
  String role;
  String walletAddress;

  User({
    required this.id,
    this.code,
    required this.name,
    required this.email,
    required this.phone,
    this.rawPhone,
    required this.countryCode,
    required this.photo,
    required this.role,
    required this.walletAddress,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      code: json['code'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'] ?? "",
      rawPhone: json['raw_phone'],
      walletAddress: json['wallet_address'] ?? "",
      countryCode: json['country_code'],
      photo: json['photo'] ?? "",
      role: json['role_name'] ?? "client",
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'code': code,
      'name': name,
      'email': email,
      'phone': phone,
      'raw_phone': rawPhone,
      'country_code': countryCode,
      'photo': photo,
      'role_name': role,
      'wallet_address': walletAddress,
    };
  }
}
