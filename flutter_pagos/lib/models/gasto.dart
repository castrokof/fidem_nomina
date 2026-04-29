class Gasto {
  final int? id;
  final String descripcion;
  final String? categoria;
  final double monto;
  final DateTime fecha;
  final String? notas;
  final String? sede;

  Gasto({
    this.id,
    required this.descripcion,
    this.categoria,
    required this.monto,
    required this.fecha,
    this.notas,
    this.sede,
  });

  Map<String, dynamic> toMap() => {
    if (id != null) 'id': id,
    'descripcion': descripcion,
    'categoria': categoria,
    'monto': monto,
    'fecha': fecha.toIso8601String(),
    'notas': notas,
    'sede': sede,
  };

  factory Gasto.fromMap(Map<String, dynamic> m) => Gasto(
    id: m['id'],
    descripcion: m['descripcion'],
    categoria: m['categoria'],
    monto: (m['monto'] as num).toDouble(),
    fecha: DateTime.parse(m['fecha']),
    notas: m['notas'],
    sede: m['sede'],
  );

  Gasto copyWith({
    int? id, String? descripcion, String? categoria,
    double? monto, DateTime? fecha, String? notas, String? sede,
  }) => Gasto(
    id: id ?? this.id,
    descripcion: descripcion ?? this.descripcion,
    categoria: categoria ?? this.categoria,
    monto: monto ?? this.monto,
    fecha: fecha ?? this.fecha,
    notas: notas ?? this.notas,
    sede: sede ?? this.sede,
  );
}
