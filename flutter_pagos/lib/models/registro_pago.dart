enum EstadoPago { pendiente, pagado, vencido }

class RegistroPago {
  final int? id;
  final int facturaId;
  final int mes;
  final int anio;
  EstadoPago estado;
  DateTime? fechaPago;
  double? montoPagado;
  String? notas;

  RegistroPago({
    this.id,
    required this.facturaId,
    required this.mes,
    required this.anio,
    this.estado = EstadoPago.pendiente,
    this.fechaPago,
    this.montoPagado,
    this.notas,
  });

  EstadoPago get estadoCalc {
    if (estado == EstadoPago.pagado) return EstadoPago.pagado;
    final hoy = DateTime.now();
    final vence = DateTime(anio, mes, diaVencimiento(anio, mes));
    if (hoy.isAfter(vence)) return EstadoPago.vencido;
    return EstadoPago.pendiente;
  }

  static int diaVencimiento(int anio, int mes) {
    // Usado externamente; aquí placeholder
    return 1;
  }

  Map<String, dynamic> toMap() => {
    if (id != null) 'id': id,
    'factura_id': facturaId,
    'mes': mes,
    'anio': anio,
    'estado': estado.name,
    'fecha_pago': fechaPago?.toIso8601String(),
    'monto_pagado': montoPagado,
    'notas': notas,
  };

  factory RegistroPago.fromMap(Map<String, dynamic> m) => RegistroPago(
    id: m['id'],
    facturaId: m['factura_id'],
    mes: m['mes'],
    anio: m['anio'],
    estado: EstadoPago.values.firstWhere(
      (e) => e.name == m['estado'],
      orElse: () => EstadoPago.pendiente,
    ),
    fechaPago: m['fecha_pago'] != null ? DateTime.tryParse(m['fecha_pago']) : null,
    montoPagado: m['monto_pagado'] != null ? (m['monto_pagado'] as num).toDouble() : null,
    notas: m['notas'],
  );
}
