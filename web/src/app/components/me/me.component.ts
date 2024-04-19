import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/user.service';
import { User } from '../../Interfaces/user-interface';
import { Router } from '@angular/router';

@Component({
  selector: 'app-me',
  standalone: true,
  imports: [],
  templateUrl: './me.component.html',
  styleUrl: './me.component.css'
})
export class MeComponent implements OnInit {

  constructor( private us: UserService , private router: Router) {}

  public user: User = {
    id: 0,
    email: "",
    name: "",
    codigoVerificadO: false,
    created_at: "",
    is_active: false,
    updated_at: ""
  }

  ngOnInit(): void {
    this.us.getData().subscribe(
      (response) => {
        this.user = response
        console.log(response);
      }
    )
  }
  Logout() {
    this.router.navigate(['/login']);
    }

}
