import { Crew } from "./Crew";
import { Document } from "./Document";
import { User } from "./User";

export type CrewDocument = {
    id: number;
    crew: Crew;
    document: Document;
    user: User;
    file_name: string;
    file_path: string;
    code: string;
    issued_date: string;
    expiry_date: string;
}