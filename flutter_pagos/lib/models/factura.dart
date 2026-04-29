class Factura {
  final int? id;
  final String nombre;
  final String? categoria;
  final String? descripcion;
  final String? referencia;
  final String? sede;
  final int diaVencimiento;
  final double montoEstimado;
  final String? correos;
  final int diasAviso;
  final bool activo;

  Factura({
    this.id,
    required this.nombre,
    this.categoria,
    this.descripcion,
    this.referencia,
    this.sede,
    required this.diaVencimiento,
    this.montoEstimado = 0,
    this.correos,
    this.diasAviso = 3,
    this.activo = true,
  });

  Map<String, dynamic> toMap() => {
    if (id != null) 'id': id,
    'nombre': nombre,
    'categoria': categoria,
    'descripcion': descripcion,
    'referencia': referencia,
    'sede': sede,
    'dia_vencimiento': diaVencimiento,
    'monto_estimado': montoEstimado,
    'correos': correos,
    'dias_aviso': diasAviso,
    'activo': activo ? 1 : 0,
  };

  factory Factura.fromMap(Map<String, dynamic> m) => Factura(
    id: m['id'],
    nombre: m['nombre'],
    categoria: m['categoria'],
    descripcion: m['descripcion'],
    referencia: m['referencia'],
    sede: m['sede'],
    diaVencimiento: m['dia_vencimiento'],
    montoEstimado: (m['monto_estimado'] as num).toDouble(),
    correos: m['correos'],
    diasAviso: m['dias_aviso'] ?? 3,
    activo: m['activo'] == 1,
  );

  Factura copyWith({
    int? id, String? nombre, String? categoria, String? descripcion,
    String? referencia, String? sede, int? diaVencimiento,
    double? montoEstimado, String? correos, int? diasAviso, bool? activo,
  }) => Factura(
    id: id ?? this.id,
    nombre: nombre ?? this.nombre,
    categoria: categoria ?? this.categoria,
    descripcion: descripcion ?? this.descripcion,
    referencia: referencia ?? this.referencia,
    sede: sede ?? this.sede,
    diaVencimiento: diaVencimiento ?? this.diaVencimiento,
    montoEstimado: montoEstimado ?? this.montoEstimado,
    correos: correos ?? this.correos,
    diasAviso: diasAviso ?? this.diasAviso,
    activo: activo ?? this.activo,
  );
}
