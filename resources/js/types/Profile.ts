
export type PersonalInformation = {
    id: string | number;
    first_name: string;
    last_name: string;
}

export type UpdatePersonalInfoForm = PersonalInformation;

export type Password = {
    id: string | number;
    current_password: string;
    password: string;
    password_confirmation: string;
}

export type UpdatePasswordForm = Password;