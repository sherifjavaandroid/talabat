class Vehicle {
  int id;
  String reg_no;
  String color;
  String carMake;
  String carModel;

  Vehicle({
    required this.id,
    required this.reg_no,
    required this.color,
    required this.carMake,
    required this.carModel,
  });

  factory Vehicle.fromJson(Map<String, dynamic> json) {
    return Vehicle(
      id: json['id'],
      reg_no: json['reg_no'],
      color: json['color'],
      carMake: json['car_model']['car_make']["name"] ?? "",
      carModel: json['car_model']["name"] ?? "",
    );
  }
  //
  String get vehicleInfo {
    return "$color $carMake $carModel";
  }
}
