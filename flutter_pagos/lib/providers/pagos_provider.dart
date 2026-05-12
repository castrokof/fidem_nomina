import 'package:flutter/material.dart';
import '../models/factura.dart';
import '../models/registro_pago.dart';
import '../services/database_service.dart';
import '../services/notification_service.dart';

class PagosProvider extends ChangeNotifier {
  List<Factura> facturas = [];
  Map<int, Map<int, RegistroPago>> registros = {};
  List<String> categorias = [];
  int anio = DateTime.now().year;
  bool loading = false;

  Future<void> cargar() async {
    loading = true;
    notifyListeners();
    facturas   = await DatabaseService.getFacturas();
    categorias = await DatabaseService.getCategorias();
    await _ensureRegistros();
    registros  = await DatabaseService.getRegistrosPorAnio(anio);
    loading    = false;
    notifyListeners();
  }

  Future<void> _ensureRegistros() async {
    final hoy = DateTime.now();
    for (final f in facturas) {
      for (int m = 1; m <= 12; m++) {
        await DatabaseService.getOrCreateRegistro(f.id!, m, anio);
      }
    }
    // Actualizar vencidos del mes actual
    for (final f in facturas) {
      final reg = await DatabaseService.getOrCreateRegistro(f.id!, hoy.month, anio);
      if (reg.estado == EstadoPago.pendiente) {
        final diasEnMes = DateTime(anio, hoy.month + 1, 0).day;
        final dia = f.diaVencimiento.clamp(1, diasEnMes);
        final vence = DateTime(anio, hoy.month, dia);
        if (hoy.isAfter(vence)) {
          reg.estado = EstadoPago.vencido;
          await DatabaseService.updateRegistro(reg);
        }
      }
    }
  }

  Future<void> cambiarAnio(int nuevoAnio) async {
    anio = nuevoAnio;
    await cargar();
  }

  RegistroPago? getRegistro(int facturaId, int mes) =>
      registros[facturaId]?[mes];

  EstadoPago estadoCelda(Factura f, int mes) {
    final reg = getRegistro(f.id!, mes);
    if (reg == null) return EstadoPago.pendiente;
    if (reg.estado == EstadoPago.pagado) return EstadoPago.pagado;
    final hoy = DateTime.now();
    final esFuturo = anio > hoy.year || (anio == hoy.year && mes > hoy.month);
    if (esFuturo) return EstadoPago.pendiente;
    final diasEnMes = DateTime(anio, mes + 1, 0).day;
    final dia = f.diaVencimiento.clamp(1, diasEnMes);
    final vence = DateTime(anio, mes, dia);
    if (hoy.isAfter(vence)) return EstadoPago.vencido;
    if (hoy.isAfter(vence.subtract(Duration(days: f.diasAviso)))) {
      return EstadoPago.pendiente; // "próximo" — tratado como pendiente con color naranja
    }
    return EstadoPago.pendiente;
  }

  bool esProximo(Factura f, int mes) {
    if (estadoCelda(f, mes) != EstadoPago.pendiente) return false;
    final hoy = DateTime.now();
    if (anio != hoy.year || mes != hoy.month) return false;
    final diasEnMes = DateTime(anio, mes + 1, 0).day;
    final dia = f.diaVencimiento.clamp(1, diasEnMes);
    final vence = DateTime(anio, mes, dia);
    return hoy.isAfter(vence.subtract(Duration(days: f.diasAviso))) &&
           hoy.isBefore(vence.add(const Duration(days: 1)));
  }

  Future<void> marcarPagado(RegistroPago reg, {
    DateTime? fechaPago, double? monto, String? notas,
  }) async {
    reg.estado     = EstadoPago.pagado;
    reg.fechaPago  = fechaPago ?? DateTime.now();
    reg.montoPagado = monto;
    reg.notas      = notas;
    await DatabaseService.updateRegistro(reg);
    registros[reg.facturaId]![reg.mes] = reg;
    notifyListeners();
  }

  Future<void> revertirPago(RegistroPago reg) async {
    reg.estado      = EstadoPago.pendiente;
    reg.fechaPago   = null;
    reg.montoPagado = null;
    await DatabaseService.updateRegistro(reg);
    registros[reg.facturaId]![reg.mes] = reg;
    notifyListeners();
  }

  Future<void> guardarFactura(Factura f) async {
    if (f.id == null) {
      final id = await DatabaseService.insertFactura(f);
      final nueva = f.copyWith(id: id);
      // Programar notificaciones para los 12 meses del año actual
      final hoy = DateTime.now();
      for (int m = hoy.month; m <= 12; m++) {
        await NotificationService.programarRecordatorio(nueva, m, anio);
      }
    } else {
      await DatabaseService.updateFactura(f);
    }
    await cargar();
  }

  Future<void> eliminarFactura(int id) async {
    await DatabaseService.deleteFactura(id);
    for (int m = 1; m <= 12; m++) {
      await NotificationService.cancelarRecordatorio(id, m);
    }
    await cargar();
  }

  Future<void> agregarCategoria(String nombre) async {
    await DatabaseService.insertCategoria(nombre);
    categorias = await DatabaseService.getCategorias();
    notifyListeners();
  }

  int get totalVencidos => facturas.fold(0, (acc, f) {
    final reg = getRegistro(f.id!, DateTime.now().month);
    return acc + (reg?.estado == EstadoPago.vencido ? 1 : 0);
  });

  int get totalProximos => facturas.fold(0, (acc, f) {
    return acc + (esProximo(f, DateTime.now().month) ? 1 : 0);
  });
}
