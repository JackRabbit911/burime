type ValidationErrors = {
  key: string;
  msg: string;
}[]

export type ApiResponse<T, E = ValidationErrors> = {
  success: boolean;
  error?: E;
  result: T;
};
