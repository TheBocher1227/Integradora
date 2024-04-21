import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http'; // Importa HttpClient
import { Observable } from 'rxjs';
import { api } from '../Interfaces/enviroment';

@Injectable({
  providedIn: 'root'
})
export class PaquetesService {
  private dataURL = `${api}/api`

  constructor(private http: HttpClient) { }

  obtenerEstaciones(token:string): Observable<any> {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get(`${this.dataURL}/obtenerestaciones`, { headers });
  }

  obtenerRegistrosPorEstacion(id: number, token:string): Observable<any> {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.post(`${this.dataURL}/obtenerRegistrosPorEstacion/${id}`, {headers});
  }

  guardarRelacionEstacion(idEstacion: number, token:string): Observable<any> {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.post(`${this.dataURL}/guardarRelacionEstacion`, { id_estacion: idEstacion }, { headers });
  }
}
