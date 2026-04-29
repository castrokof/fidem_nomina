import 'package:flutter/material.dart';
import '../models/gasto.dart';
import '../services/database_service.dart';

class GastosProvider extends ChangeNotifier {
  List<Gasto> gastos = [];
  List<String> categorias = [];
  Map<String, double> porCategoria = {};
  double totalMes = 0;
  int mes = DateTime.now().month;
  int anio = DateTime.now().year;
  bool loading = false;

  static const List<String> meses = [
    '', 'Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',
  ];

  String get mesNombre => meses[mes];

  Future<void> cargar() async {
    loading = true;
    notifyListeners();
    gastos       = await DatabaseService.getGastos(mes: mes, anio: anio);
    categorias   = await DatabaseService.getCategorias(tipo: 'gasto');
    porCategoria = await DatabaseService.getGastosPorCategoria(mes: mes, anio: anio);
    totalMes     = await DatabaseService.getTotalGastos(mes: mes, anio: anio);
    loading      = false;
    notifyListeners();
  }

  Future<void> cambiarMes(int nuevoMes, int nuevoAnio) async {
    mes  = nuevoMes;
    anio = nuevoAnio;
    await cargar();
  }

  Future<void> guardarGasto(Gasto g) async {
    if (g.id == null) {
      await DatabaseService.insertGasto(g);
    } else {
      await DatabaseService.updateGasto(g);
    }
    await cargar();
  }

  Future<void> eliminarGasto(int id) async {
    await DatabaseService.deleteGasto(id);
    await cargar();
  }

  Future<void> agregarCategoria(String nombre) async {
    await DatabaseService.insertCategoria(nombre, tipo: 'gasto');
    categorias = await DatabaseService.getCategorias(tipo: 'gasto');
    notifyListeners();
  }

  void mesAnterior() {
    if (mes == 1) { mes = 12; anio--; } else { mes--; }
    cargar();
  }

  void mesSiguiente() {
    final hoy = DateTime.now();
    if (anio == hoy.year && mes == hoy.month) return;
    if (mes == 12) { mes = 1; anio++; } else { mes++; }
    cargar();
  }
}
