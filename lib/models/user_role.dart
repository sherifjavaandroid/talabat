class UserRole {
  final int id;
  final String name;
  final String guardName;
  final List<RolePermission> permissions;

  UserRole({
    required this.id,
    required this.name,
    required this.guardName,
    this.permissions = const [],
  });

  factory UserRole.fromJson(Map<String, dynamic> json) => UserRole(
        id: json["id"],
        name: json["name"],
        guardName: json["guard_name"],
        permissions: json["permissions"] == null
            ? []
            : List<RolePermission>.from(
                json["permissions"].map(
                  (x) => RolePermission.fromJson(x),
                ),
              ),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "guard_name": guardName,
        "permissions": List<dynamic>.from(
          permissions.map(
            (x) => x.toJson(),
          ),
        ),
      };
}

//RolePermission
class RolePermission {
  final int id;
  final String name;
  final String? guardName;

  RolePermission({
    required this.id,
    required this.name,
    required this.guardName,
  });

  factory RolePermission.fromJson(Map<String, dynamic> json) => RolePermission(
        id: json["id"],
        name: json["name"],
        guardName: json["guard_name"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "guard_name": guardName,
      };
}
