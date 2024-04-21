import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router } from '@angular/router';
import { Observable, of } from 'rxjs';
import { AuthService } from './services/auth.service';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class Auth2Guard implements CanActivate {
  constructor(private authService: AuthService, private router: Router) {}

  canActivate(route: ActivatedRouteSnapshot): Observable<boolean> {
    const token = localStorage.getItem('token');
    if (!token) {
      this.router.navigate(['/login']);
      return of(false);
    }

    return this.authService.verifyToken(token).pipe(
      map((response) => {
        return response.status === 200; 
      }),
      catchError((error) => {
        this.router.navigate(['/login']); 
        return of(false);
      })
    );
  }
}
