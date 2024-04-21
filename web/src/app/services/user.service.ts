import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { api } from '../Interfaces/enviroment';
import { Observable } from 'rxjs';
import { User } from '../Interfaces/user-interface';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(private http: HttpClient) { }

  private dataURL = `${api}/api/user/me`
  private registroURL = `${api}/api/user/registrobatalla/`

  getData(token:string): Observable<User> {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    return this.http.get<User>(this.dataURL , { headers})
  }

  // getBatallas(): Observable<>

}
